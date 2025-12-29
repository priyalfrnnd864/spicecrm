# Zero-Shot Security Architecture - Proof of Concept

## Overview

This directory contains a proof-of-concept implementation of a zero-shot security architecture for SpiceCRM. The zero-shot approach allows the system to make intelligent access control decisions without requiring explicit pre-configuration for every permission scenario.

## What is Zero-Shot Security?

Zero-shot security uses:
- **Attribute-Based Access Control (ABAC)**: Makes decisions based on user, resource, and context attributes
- **Rule-Based Inference**: Applies intelligent rules to determine access
- **Anomaly Detection**: Identifies unusual access patterns
- **Confidence Scoring**: Provides confidence level for each decision
- **Hybrid Approach**: Can work alongside traditional ACL for gradual adoption

## Components

### Core Classes

1. **AccessRequest.php**
   - Represents an access request with user, resource, action, and context
   - Extracts attributes for ABAC evaluation

2. **AccessDecision.php**
   - Represents the result of an access evaluation
   - Contains allowed/denied status, confidence score, and reasoning

3. **ZeroShotPolicyEngineI.php**
   - Interface for policy engine implementations
   - Defines methods for evaluation, learning, and explanation

4. **ZeroShotPolicyEngine.php**
   - Main implementation of the zero-shot policy engine
   - Applies ABAC rules with confidence scoring
   - Detects anomalies
   - Provides explanations

5. **DecisionLogger.php**
   - Logs access decisions for monitoring and analysis
   - Alerts on anomalies
   - Tracks statistics

6. **HybridSecurityController.php**
   - Combines traditional ACL with zero-shot
   - Routes requests based on configuration
   - Falls back to ACL for low-confidence decisions

## How It Works

### Rule-Based Inference

The policy engine applies these rules in order:

1. **Admin Rule**: Admins get full access (confidence: 1.0)
2. **API User Rule**: API users get read-only access (confidence: 0.95)
3. **Portal User Rule**: Portal users limited to specific modules (confidence: 0.90)
4. **Owner Rule**: Users can access resources they own (confidence: 0.88)
5. **Department Rule**: Users can view resources in their department (confidence: 0.82)
6. **Default Read Rule**: Default read-only for non-sensitive modules (confidence: 0.70)
7. **Default Deny**: No matching rule defaults to deny (confidence: 0.60)

### Confidence Threshold

- Decisions with confidence >= threshold (default: 0.85) are used directly
- Lower confidence decisions fall back to traditional ACL in hybrid mode
- This ensures safety while gaining benefits of zero-shot

### Anomaly Detection

The engine detects anomalies such as:
- Access outside business hours
- Sensitive actions on sensitive modules
- Unusual access patterns

## Configuration

Copy `config.example.php` to your `config/config.php` and customize:

```php
return [
    'security' => [
        'mode' => 'hybrid', // or 'traditional' or 'zeroshot'
        'zeroshot' => [
            'enabled' => true,
            'confidence_threshold' => 0.85,
            'fallback_to_acl' => true,
            'anomaly_detection' => true,
            'logging' => [
                'log_all_decisions' => true,
                'log_low_confidence' => true,
                'alert_on_anomaly' => true,
            ],
        ],
    ],
];
```

## Usage Example

### Basic Usage

```php
use SpiceCRM\includes\authentication\ZeroShot\HybridSecurityController;
use SpiceCRM\includes\authentication\AuthenticationController;

$security = new HybridSecurityController();
$user = AuthenticationController::getInstance()->getCurrentUser();
$resource = BeanFactory::getBean('Accounts', $accountId);
$action = 'edit';

if ($security->checkAccess($user, $resource, $action)) {
    // Allow access
} else {
    // Deny access
}
```

### Direct Zero-Shot Evaluation

```php
use SpiceCRM\includes\authentication\ZeroShot\ZeroShotPolicyEngine;
use SpiceCRM\includes\authentication\ZeroShot\AccessRequest;

$engine = new ZeroShotPolicyEngine();
$request = new AccessRequest($user, $resource, $action);
$decision = $engine->evaluateAccess($request);

echo "Decision: " . ($decision->allowed ? 'ALLOWED' : 'DENIED') . "\n";
echo "Confidence: " . ($decision->confidence * 100) . "%\n";
echo "Reasoning: " . $decision->reasoning . "\n";
```

### Get Decision Explanation

```php
$explanation = $engine->explainDecision($decision->decisionId);
echo $explanation;
```

## Integration with SpiceACL

The zero-shot system is designed to work alongside the existing SpiceACL:

1. **Traditional Mode**: Uses only SpiceACL (default)
2. **Hybrid Mode**: Uses zero-shot for high-confidence decisions, falls back to SpiceACL
3. **Zero-Shot Mode**: Uses only zero-shot (with optional fallback)

This allows gradual adoption without disrupting existing security.

## Benefits

1. **Reduced Configuration**: Less manual permission setup
2. **Adaptability**: Handles new scenarios without configuration
3. **Context-Aware**: Considers time, location, and other factors
4. **Explainable**: Provides reasoning for decisions
5. **Safe Migration**: Can be adopted incrementally

## Limitations

This is a proof-of-concept implementation with limitations:

1. **Rule-Based Only**: Uses predefined rules, not ML inference
2. **No Database Logging**: Logs to files, not database
3. **No ML Integration**: OpenAI/Azure integration not implemented
4. **Simple Anomaly Detection**: Basic heuristics only
5. **No UI**: Command-line/API only

## Future Enhancements

1. **ML Integration**: Connect to OpenAI, Azure ML, or local models
2. **Embedding-Based Similarity**: Use embeddings for similarity-based decisions
3. **Natural Language Policies**: Parse policies in natural language
4. **Advanced Anomaly Detection**: ML-based anomaly detection
5. **Feedback Loop**: Learn from user feedback
6. **Admin UI**: Web interface for monitoring and configuration
7. **Database Schema**: Proper database tables for logging

## Testing

To test the zero-shot system:

1. Enable in config:
   ```php
   'security' => ['mode' => 'hybrid', 'zeroshot' => ['enabled' => true]]
   ```

2. Monitor logs in `spicecrm.log`

3. Check decision statistics:
   ```php
   $stats = $security->getStatistics();
   print_r($stats);
   ```

## Security Considerations

- **Start in Hybrid Mode**: Don't disable traditional ACL until confident
- **Monitor Logs**: Watch for anomalies and low-confidence decisions
- **Adjust Threshold**: Tune confidence threshold based on your needs
- **Test Thoroughly**: Test with various users and scenarios
- **Backup Plan**: Keep traditional ACL as fallback

## Support

For questions or issues:
- See main documentation: `/docs/zero-shot-security-architecture.md`
- Check logs: `spicecrm.log`
- Review statistics via `HybridSecurityController::getStatistics()`

## License

Same as SpiceCRM - see LICENSE file
