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
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\Logger\LoggerManager;

/**
 * Zero-Shot Policy Engine Implementation
 * 
 * This implementation uses attribute-based access control (ABAC) with rule-based
 * inference to make access decisions without requiring explicit pre-configuration
 * for every scenario.
 */
class ZeroShotPolicyEngine implements ZeroShotPolicyEngineI
{
    /**
     * @var float Confidence threshold
     */
    private $confidenceThreshold;

    /**
     * @var array Decision cache
     */
    private $decisionCache = [];

    /**
     * @var DecisionLogger Decision logger
     */
    private $logger;

    /**
     * @var array Stored decisions for explanation
     */
    private $decisions = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = SpiceConfig::getInstance()->config;
        $this->confidenceThreshold = $config['security']['zeroshot']['confidence_threshold'] ?? 0.85;
        $this->logger = new DecisionLogger();
    }

    /**
     * Evaluate access request using zero-shot inference
     * 
     * @param AccessRequest $request
     * @return AccessDecision
     */
    public function evaluateAccess(AccessRequest $request): AccessDecision
    {
        // Check cache first
        $cacheKey = $this->getCacheKey($request);
        if (isset($this->decisionCache[$cacheKey])) {
            return $this->decisionCache[$cacheKey];
        }

        // Apply rule-based inference
        $decision = $this->applyABACRules($request);

        // Check for anomalies
        if ($this->detectAnomaly($request)) {
            $decision->setAnomalyDetected(true);
            $decision->addMetadata('anomaly_reason', 'Unusual access pattern detected');
        }

        // Cache and store decision
        $this->decisionCache[$cacheKey] = $decision;
        $this->decisions[$decision->decisionId] = $decision;

        // Log decision
        $this->logger->logDecision($request, $decision);

        return $decision;
    }

    /**
     * Apply attribute-based access control rules
     * 
     * @param AccessRequest $request
     * @return AccessDecision
     */
    private function applyABACRules(AccessRequest $request): AccessDecision
    {
        $allowed = false;
        $confidence = 0.0;
        $reasoning = '';

        // Rule 1: Admin users have full access
        if ($request->userAttributes['is_admin']) {
            $allowed = true;
            $confidence = 1.0;
            $reasoning = 'User is administrator with full system access';
            return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_rule_admin');
        }

        // Rule 2: API users restricted to API actions only
        if ($request->userAttributes['is_api_user']) {
            if (in_array($request->action, ['list', 'view', 'export'])) {
                $allowed = true;
                $confidence = 0.95;
                $reasoning = 'API user has read-only access';
            } else {
                $allowed = false;
                $confidence = 0.95;
                $reasoning = 'API user does not have write access';
            }
            return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_rule_api');
        }

        // Rule 3: Portal users restricted to portal-accessible modules
        if ($request->userAttributes['portal_only']) {
            $portalModules = ['Contacts', 'Cases', 'KnowledgeBase', 'Documents'];
            $module = $request->resourceAttributes['module'] ?? '';
            
            if (in_array($module, $portalModules)) {
                $allowed = true;
                $confidence = 0.90;
                $reasoning = "Portal user has access to {$module} module";
            } else {
                $allowed = false;
                $confidence = 0.95;
                $reasoning = "Portal user cannot access {$module} module";
            }
            return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_rule_portal');
        }

        // Rule 4: Owner-based access (if resource has assigned_user_id)
        if (isset($request->resourceAttributes['assigned_user_id'])) {
            if ($request->resourceAttributes['assigned_user_id'] === $request->userAttributes['id']) {
                $allowed = true;
                $confidence = 0.88;
                $reasoning = 'User is the owner of this resource';
                return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_rule_owner');
            }
        }

        // Rule 5: Department-based access (if both user and resource have department)
        if (!empty($request->userAttributes['department']) && 
            isset($request->resourceAttributes['department']) &&
            $request->userAttributes['department'] === $request->resourceAttributes['department']) {
            
            // Same department: allow view/list, restrict edit/delete
            if (in_array($request->action, ['view', 'list'])) {
                $allowed = true;
                $confidence = 0.82;
                $reasoning = 'User and resource are in the same department (read access)';
            } else {
                $allowed = false;
                $confidence = 0.75;
                $reasoning = 'User and resource are in the same department but user is not owner (no write access)';
            }
            return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_rule_department');
        }

        // Rule 6: Read-only actions for non-sensitive modules (default behavior)
        $readOnlyActions = ['view', 'list', 'export'];
        $sensitiveDeniedModules = ['Users', 'Administration', 'SystemSettings'];
        $module = $request->resourceAttributes['module'] ?? '';

        if (!in_array($module, $sensitiveDeniedModules) && in_array($request->action, $readOnlyActions)) {
            $allowed = true;
            $confidence = 0.70;
            $reasoning = "Default read-only access for {$module} module";
            return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_rule_default_read');
        }

        // Default deny with low confidence
        $allowed = false;
        $confidence = 0.60;
        $reasoning = 'No matching rule found, defaulting to deny';
        
        return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning, 'zeroshot_default_deny');
    }

    /**
     * Learn from access patterns
     * 
     * @param array $accessLog
     * @return void
     */
    public function learn(array $accessLog): void
    {
        // In a production system, this would:
        // 1. Store the access log in a database
        // 2. Update ML models with new patterns
        // 3. Adjust confidence scores based on feedback
        
        // For POC, we just log it
        LoggerManager::getLogger()->debug('ZeroShot: Learning from access log', $accessLog);
    }

    /**
     * Explain a decision
     * 
     * @param string $decisionId
     * @return string
     */
    public function explainDecision(string $decisionId): string
    {
        if (isset($this->decisions[$decisionId])) {
            $decision = $this->decisions[$decisionId];
            return sprintf(
                "Decision %s: %s (Confidence: %.2f%%). Reasoning: %s. Source: %s",
                $decision->decisionId,
                $decision->allowed ? 'ALLOWED' : 'DENIED',
                $decision->confidence * 100,
                $decision->reasoning,
                $decision->source
            );
        }

        return "Decision not found";
    }

    /**
     * Detect anomalies in access patterns
     * 
     * @param AccessRequest $request
     * @return bool
     */
    public function detectAnomaly(AccessRequest $request): bool
    {
        // Simple anomaly detection heuristics
        
        // Anomaly 1: Access outside business hours (if configured)
        $config = SpiceConfig::getInstance()->config;
        if (isset($config['security']['zeroshot']['anomaly_detection']) && 
            $config['security']['zeroshot']['anomaly_detection']) {
            
            $hour = (int)date('H');
            if ($hour < 6 || $hour > 22) {
                LoggerManager::getLogger()->warn('ZeroShot: Access outside business hours detected', [
                    'user' => $request->userAttributes['username'],
                    'hour' => $hour
                ]);
                return true;
            }
        }

        // Anomaly 2: Sensitive action on sensitive module
        $sensitiveModules = ['Users', 'Administration', 'SystemSettings', 'ACLRoles'];
        $sensitiveActions = ['delete', 'massupdate', 'export'];
        
        $module = $request->resourceAttributes['module'] ?? '';
        if (in_array($module, $sensitiveModules) && in_array($request->action, $sensitiveActions)) {
            LoggerManager::getLogger()->warn('ZeroShot: Sensitive action on sensitive module', [
                'user' => $request->userAttributes['username'],
                'module' => $module,
                'action' => $request->action
            ]);
            // Don't return true here, just log - let the rules decide
        }

        return false;
    }

    /**
     * Get confidence threshold
     * 
     * @return float
     */
    public function getConfidenceThreshold(): float
    {
        return $this->confidenceThreshold;
    }

    /**
     * Set confidence threshold
     * 
     * @param float $threshold
     * @return void
     */
    public function setConfidenceThreshold(float $threshold): void
    {
        $this->confidenceThreshold = max(0.0, min(1.0, $threshold));
    }

    /**
     * Generate cache key for request
     * 
     * @param AccessRequest $request
     * @return string
     */
    private function getCacheKey(AccessRequest $request): string
    {
        return md5(json_encode([
            'user' => $request->userAttributes['id'],
            'resource' => $request->resourceAttributes,
            'action' => $request->action,
        ]));
    }

    /**
     * Clear decision cache
     * 
     * @return void
     */
    public function clearCache(): void
    {
        $this->decisionCache = [];
    }
}
