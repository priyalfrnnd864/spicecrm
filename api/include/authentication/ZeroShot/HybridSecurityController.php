<?php
/*********************************************************************************
 * This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
 * and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 * You can contact us at info@spicecrm.io
 * 
 * SpiceCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * 
 * SpiceCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ********************************************************************************/

namespace SpiceCRM\includes\authentication\ZeroShot;

use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\includes\Logger\LoggerManager;
use SpiceCRM\modules\SpiceACL\SpiceACL;
use SpiceCRM\modules\Users\User;

/**
 * Hybrid Security Controller
 * 
 * Combines traditional ACL with zero-shot security for intelligent access control
 */
class HybridSecurityController
{
    /**
     * @var SpiceACL Traditional ACL instance
     */
    private $traditionalACL;

    /**
     * @var ZeroShotPolicyEngine Zero-shot engine
     */
    private $zeroShotEngine;

    /**
     * @var string Security mode (traditional, hybrid, zeroshot)
     */
    private $securityMode;

    /**
     * @var bool Whether zero-shot is enabled
     */
    private $zeroShotEnabled;

    /**
     * @var bool Whether to fallback to ACL
     */
    private $fallbackToACL;

    /**
     * @var float Confidence threshold
     */
    private $confidenceThreshold;

    /**
     * @var array Statistics
     */
    private $stats = [
        'zeroshot_decisions' => 0,
        'acl_decisions' => 0,
        'fallbacks' => 0,
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = SpiceConfig::getInstance()->config;
        
        $this->securityMode = $config['security']['mode'] ?? 'traditional';
        $this->zeroShotEnabled = $config['security']['zeroshot']['enabled'] ?? false;
        $this->fallbackToACL = $config['security']['zeroshot']['fallback_to_acl'] ?? true;
        $this->confidenceThreshold = $config['security']['zeroshot']['confidence_threshold'] ?? 0.85;
        
        $this->traditionalACL = SpiceACL::getInstance();
        
        if ($this->zeroShotEnabled) {
            $this->zeroShotEngine = new ZeroShotPolicyEngine();
            $this->zeroShotEngine->setConfidenceThreshold($this->confidenceThreshold);
        }
    }

    /**
     * Check access using hybrid approach
     * 
     * @param User $user
     * @param mixed $resource (SpiceBean or module name)
     * @param string $action
     * @return bool
     */
    public function checkAccess(User $user, $resource, string $action): bool
    {
        // Traditional mode: use only ACL
        if ($this->securityMode === 'traditional' || !$this->zeroShotEnabled) {
            $this->stats['acl_decisions']++;
            return $this->checkTraditionalACL($user, $resource, $action);
        }

        // Zero-shot only mode
        if ($this->securityMode === 'zeroshot' && !$this->fallbackToACL) {
            $this->stats['zeroshot_decisions']++;
            return $this->checkZeroShot($user, $resource, $action);
        }

        // Hybrid mode: try zero-shot first, fallback to ACL
        if ($this->securityMode === 'hybrid' || ($this->securityMode === 'zeroshot' && $this->fallbackToACL)) {
            return $this->checkHybrid($user, $resource, $action);
        }

        // Default to traditional ACL
        $this->stats['acl_decisions']++;
        return $this->checkTraditionalACL($user, $resource, $action);
    }

    /**
     * Check access using hybrid approach
     * 
     * @param User $user
     * @param mixed $resource
     * @param string $action
     * @return bool
     */
    private function checkHybrid(User $user, $resource, string $action): bool
    {
        // Try zero-shot first
        $request = new AccessRequest($user, $resource, $action, $this->getContextAttributes());
        $decision = $this->zeroShotEngine->evaluateAccess($request);

        // If confidence is high, use zero-shot decision
        if ($decision->isHighConfidence($this->confidenceThreshold)) {
            $this->stats['zeroshot_decisions']++;
            
            LoggerManager::getLogger()->debug('Using zero-shot decision', [
                'decisionId' => $decision->decisionId,
                'confidence' => $decision->confidence,
                'allowed' => $decision->allowed,
            ]);
            
            return $decision->allowed;
        }

        // Confidence is low, fallback to traditional ACL
        $this->stats['fallbacks']++;
        
        LoggerManager::getLogger()->debug('Falling back to traditional ACL', [
            'decisionId' => $decision->decisionId,
            'confidence' => $decision->confidence,
            'reason' => 'Low confidence',
        ]);

        return $this->checkTraditionalACL($user, $resource, $action);
    }

    /**
     * Check access using zero-shot only
     * 
     * @param User $user
     * @param mixed $resource
     * @param string $action
     * @return bool
     */
    private function checkZeroShot(User $user, $resource, string $action): bool
    {
        $request = new AccessRequest($user, $resource, $action, $this->getContextAttributes());
        $decision = $this->zeroShotEngine->evaluateAccess($request);
        
        return $decision->allowed;
    }

    /**
     * Check access using traditional ACL
     * 
     * @param User $user
     * @param mixed $resource
     * @param string $action
     * @return bool
     */
    private function checkTraditionalACL(User $user, $resource, string $action): bool
    {
        // Use existing SpiceACL logic
        return $this->traditionalACL->checkAccess($resource, $action);
    }

    /**
     * Get context attributes for zero-shot evaluation
     * 
     * @return array
     */
    private function getContextAttributes(): array
    {
        return [
            'timestamp' => time(),
            'hour' => (int)date('H'),
            'day_of_week' => date('l'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        ];
    }

    /**
     * Get statistics
     * 
     * @return array
     */
    public function getStatistics(): array
    {
        return $this->stats;
    }

    /**
     * Get security mode
     * 
     * @return string
     */
    public function getSecurityMode(): string
    {
        return $this->securityMode;
    }

    /**
     * Check if zero-shot is enabled
     * 
     * @return bool
     */
    public function isZeroShotEnabled(): bool
    {
        return $this->zeroShotEnabled;
    }
}
