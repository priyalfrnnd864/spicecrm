<?php
/**
 * Zero-Shot Security Configuration Example
 * 
 * Add this configuration to your config/config.php file to enable zero-shot security
 */

return [
    'security' => [
        // Security mode: 'traditional', 'hybrid', or 'zeroshot'
        // - traditional: Uses only the existing SpiceACL system
        // - hybrid: Uses zero-shot for high-confidence decisions, falls back to ACL
        // - zeroshot: Uses only zero-shot (with optional fallback)
        'mode' => 'hybrid',
        
        'zeroshot' => [
            // Enable/disable zero-shot security
            'enabled' => true,
            
            // Confidence threshold (0.0 - 1.0)
            // Only use zero-shot decisions if confidence is above this threshold
            'confidence_threshold' => 0.85,
            
            // Whether to fallback to traditional ACL when confidence is low
            'fallback_to_acl' => true,
            
            // Enable learning from access patterns
            'learning_enabled' => true,
            
            // Enable anomaly detection
            'anomaly_detection' => true,
            
            // Provide explanations for access decisions
            'explain_decisions' => true,
            
            // ML model configuration (for future integration)
            'model' => [
                // Provider: 'openai', 'azure', 'local', or 'none'
                'provider' => 'none',
                
                // API configuration (for external ML providers)
                'api_key' => null, // Use environment variable: getenv('OPENAI_API_KEY')
                'api_endpoint' => null,
                
                // Model names
                'model_name' => 'gpt-4',
                'embedding_model' => 'text-embedding-ada-002',
            ],
            
            // Attribute-Based Access Control (ABAC) configuration
            'attributes' => [
                // User attributes to consider
                'user' => [
                    'department',
                    'title',
                    'employee_status',
                    'is_admin',
                    'portal_only',
                    'is_api_user',
                ],
                
                // Resource attributes to consider
                'resource' => [
                    'module',
                    'assigned_user_id',
                    'created_by',
                    'department',
                    'sensitivity',
                ],
                
                // Context attributes to consider
                'context' => [
                    'time',
                    'day_of_week',
                    'ip_address',
                    'device_type',
                    'network_type',
                ],
            ],
            
            // Logging configuration
            'logging' => [
                // Log all access decisions
                'log_all_decisions' => true,
                
                // Log low-confidence decisions for review
                'log_low_confidence' => true,
                
                // Alert on anomaly detection
                'alert_on_anomaly' => true,
                
                // Database table for decision logs
                'decision_log_table' => 'zeroshot_decision_log',
                
                // Retention period for logs (in days)
                'retention_days' => 90,
            ],
            
            // Performance configuration
            'performance' => [
                // Enable decision caching
                'cache_enabled' => true,
                
                // Cache TTL in seconds
                'cache_ttl' => 300,
                
                // Maximum cache size
                'max_cache_size' => 1000,
            ],
            
            // Security configuration
            'security' => [
                // Modules that require traditional ACL (bypass zero-shot)
                'force_traditional_acl_modules' => [
                    'Administration',
                    'SystemSettings',
                ],
                
                // Actions that require traditional ACL
                'force_traditional_acl_actions' => [
                    'massupdate',
                    'massdelelete',
                ],
                
                // Automatically deny these patterns
                'auto_deny_patterns' => [
                    // Example: deny delete on Users module
                    // ['module' => 'Users', 'action' => 'delete']
                ],
            ],
        ],
    ],
];
