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
 * Represents the result of a zero-shot access evaluation
 */
class AccessDecision
{
    /**
     * @var string Unique decision ID
     */
    public $decisionId;

    /**
     * @var string Request ID this decision is for
     */
    public $requestId;

    /**
     * @var bool Whether access is allowed
     */
    public $allowed;

    /**
     * @var float Confidence score (0.0 - 1.0)
     */
    public $confidence;

    /**
     * @var string Explanation of the decision
     */
    public $reasoning;

    /**
     * @var string Source of the decision (zeroshot, traditional_acl, hybrid)
     */
    public $source;

    /**
     * @var array Additional metadata
     */
    public $metadata;

    /**
     * @var float Timestamp when decision was made
     */
    public $timestamp;

    /**
     * @var bool Whether this decision triggered an anomaly alert
     */
    public $anomalyDetected;

    /**
     * Constructor
     * 
     * @param string $requestId
     * @param bool $allowed
     * @param float $confidence
     * @param string $reasoning
     * @param string $source
     */
    public function __construct(
        string $requestId,
        bool $allowed,
        float $confidence,
        string $reasoning = '',
        string $source = 'zeroshot'
    ) {
        $this->decisionId = $this->generateDecisionId();
        $this->requestId = $requestId;
        $this->allowed = $allowed;
        $this->confidence = max(0.0, min(1.0, $confidence)); // Clamp between 0 and 1
        $this->reasoning = $reasoning;
        $this->source = $source;
        $this->timestamp = microtime(true);
        $this->anomalyDetected = false;
        $this->metadata = [];
    }

    /**
     * Generate unique decision ID
     * 
     * @return string
     */
    private function generateDecisionId(): string
    {
        return uniqid('dec_', true);
    }

    /**
     * Set anomaly detection flag
     * 
     * @param bool $detected
     * @return self
     */
    public function setAnomalyDetected(bool $detected): self
    {
        $this->anomalyDetected = $detected;
        return $this;
    }

    /**
     * Add metadata to decision
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function addMetadata(string $key, $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }

    /**
     * Check if confidence is above threshold
     * 
     * @param float $threshold
     * @return bool
     */
    public function isHighConfidence(float $threshold = 0.85): bool
    {
        return $this->confidence >= $threshold;
    }

    /**
     * Convert decision to array for logging/storage
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'decisionId' => $this->decisionId,
            'requestId' => $this->requestId,
            'allowed' => $this->allowed,
            'confidence' => $this->confidence,
            'reasoning' => $this->reasoning,
            'source' => $this->source,
            'timestamp' => $this->timestamp,
            'anomalyDetected' => $this->anomalyDetected,
            'metadata' => $this->metadata,
        ];
    }

    /**
     * Convert decision to JSON
     * 
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
