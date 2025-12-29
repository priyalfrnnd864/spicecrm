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

/**
 * Interface for Zero-Shot Policy Engine implementations
 */
interface ZeroShotPolicyEngineI
{
    /**
     * Evaluate access request using zero-shot inference
     * 
     * @param AccessRequest $request Contains user, resource, action, and context
     * @return AccessDecision Decision with confidence score and reasoning
     */
    public function evaluateAccess(AccessRequest $request): AccessDecision;

    /**
     * Learn from access patterns to improve future decisions
     * 
     * @param array $accessLog Historical access log entry
     * @return void
     */
    public function learn(array $accessLog): void;

    /**
     * Explain why a decision was made
     * 
     * @param string $decisionId
     * @return string Human-readable explanation
     */
    public function explainDecision(string $decisionId): string;

    /**
     * Detect if an access pattern is anomalous
     * 
     * @param AccessRequest $request
     * @return bool True if pattern is anomalous
     */
    public function detectAnomaly(AccessRequest $request): bool;

    /**
     * Get confidence threshold for using zero-shot decisions
     * 
     * @return float Threshold value (0.0 - 1.0)
     */
    public function getConfidenceThreshold(): float;

    /**
     * Set confidence threshold
     * 
     * @param float $threshold
     * @return void
     */
    public function setConfidenceThreshold(float $threshold): void;
}
