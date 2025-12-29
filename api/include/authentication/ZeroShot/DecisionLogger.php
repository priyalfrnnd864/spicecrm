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

use SpiceCRM\includes\Logger\LoggerManager;
use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\includes\database\DBManagerFactory;

/**
 * Logger for zero-shot access decisions
 */
class DecisionLogger
{
    /**
     * @var bool Whether logging is enabled
     */
    private $loggingEnabled;

    /**
     * @var bool Whether to log low confidence decisions
     */
    private $logLowConfidence;

    /**
     * @var bool Whether to log all decisions
     */
    private $logAllDecisions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = SpiceConfig::getInstance()->config;
        $this->loggingEnabled = $config['security']['zeroshot']['logging']['log_all_decisions'] ?? true;
        $this->logLowConfidence = $config['security']['zeroshot']['logging']['log_low_confidence'] ?? true;
        $this->logAllDecisions = $config['security']['zeroshot']['logging']['log_all_decisions'] ?? true;
    }

    /**
     * Log an access decision
     * 
     * @param AccessRequest $request
     * @param AccessDecision $decision
     * @return void
     */
    public function logDecision(AccessRequest $request, AccessDecision $decision): void
    {
        if (!$this->loggingEnabled) {
            return;
        }

        // Always log anomalies
        if ($decision->anomalyDetected) {
            $this->logAnomaly($request, $decision);
        }

        // Log low confidence decisions if configured
        if ($this->logLowConfidence && !$decision->isHighConfidence()) {
            $this->logLowConfidenceDecision($request, $decision);
        }

        // Log all decisions if configured
        if ($this->logAllDecisions) {
            $this->logToDatabase($request, $decision);
        }

        // Also log to system logger
        LoggerManager::getLogger()->debug('ZeroShot Decision', [
            'decisionId' => $decision->decisionId,
            'requestId' => $request->requestId,
            'user' => $request->userAttributes['username'],
            'module' => $request->resourceAttributes['module'] ?? 'unknown',
            'action' => $request->action,
            'allowed' => $decision->allowed,
            'confidence' => $decision->confidence,
            'source' => $decision->source,
        ]);
    }

    /**
     * Log anomaly to system
     * 
     * @param AccessRequest $request
     * @param AccessDecision $decision
     * @return void
     */
    private function logAnomaly(AccessRequest $request, AccessDecision $decision): void
    {
        LoggerManager::getLogger()->warn('ZeroShot Anomaly Detected', [
            'decisionId' => $decision->decisionId,
            'user' => $request->userAttributes['username'],
            'module' => $request->resourceAttributes['module'] ?? 'unknown',
            'action' => $request->action,
            'reasoning' => $decision->reasoning,
            'metadata' => $decision->metadata,
        ]);

        // TODO: Send alert to security team if configured
    }

    /**
     * Log low confidence decision
     * 
     * @param AccessRequest $request
     * @param AccessDecision $decision
     * @return void
     */
    private function logLowConfidenceDecision(AccessRequest $request, AccessDecision $decision): void
    {
        LoggerManager::getLogger()->info('ZeroShot Low Confidence Decision', [
            'decisionId' => $decision->decisionId,
            'user' => $request->userAttributes['username'],
            'confidence' => $decision->confidence,
            'reasoning' => $decision->reasoning,
        ]);
    }

    /**
     * Log decision to database for analytics
     * 
     * @param AccessRequest $request
     * @param AccessDecision $decision
     * @return void
     */
    private function logToDatabase(AccessRequest $request, AccessDecision $decision): void
    {
        try {
            $db = DBManagerFactory::getInstance();
            
            // Note: This requires a database table to be created
            // For POC, we'll just log to the system logger instead
            // In production, you would create a table like:
            // CREATE TABLE zeroshot_decision_log (
            //     id VARCHAR(36) PRIMARY KEY,
            //     request_id VARCHAR(36),
            //     user_id VARCHAR(36),
            //     module VARCHAR(100),
            //     action VARCHAR(50),
            //     allowed TINYINT(1),
            //     confidence DECIMAL(5,4),
            //     reasoning TEXT,
            //     source VARCHAR(50),
            //     anomaly_detected TINYINT(1),
            //     timestamp DATETIME,
            //     metadata TEXT
            // );
            
            LoggerManager::getLogger()->debug('ZeroShot Decision Log Entry', [
                'decision' => $decision->toArray(),
                'request' => $request->toArray(),
            ]);
            
        } catch (\Exception $e) {
            LoggerManager::getLogger()->error('Failed to log decision to database', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get decision statistics
     * 
     * @param int $limit
     * @return array
     */
    public function getStatistics(int $limit = 100): array
    {
        // In production, this would query the database
        // For POC, return empty array
        return [
            'total_decisions' => 0,
            'allowed' => 0,
            'denied' => 0,
            'avg_confidence' => 0.0,
            'anomalies' => 0,
        ];
    }
}
