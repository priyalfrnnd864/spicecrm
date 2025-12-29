# Migration Guide: Traditional ACL to Zero-Shot Security

## Overview

This guide provides step-by-step instructions for migrating from traditional SpiceACL to the zero-shot security architecture. The migration follows a phased approach to ensure safety and allow for testing at each stage.

## Prerequisites

Before beginning the migration:

1. **Backup**: Full backup of database and code
2. **Test Environment**: Set up test environment that mirrors production
3. **Monitoring**: Ensure logging and monitoring are in place
4. **Documentation**: Review current ACL configuration and policies
5. **Stakeholder Buy-in**: Get approval from security and management teams

## Migration Phases

### Phase 1: Installation and Shadow Mode (Weeks 1-2)

**Objective**: Install zero-shot components and run in shadow mode (logging only, not enforcing)

#### Steps:

1. **Deploy Code**
   ```bash
   # Deploy the zero-shot components
   git pull origin main
   # Verify files are present
   ls -la api/include/authentication/ZeroShot/
   ```

2. **Configure Shadow Mode**
   
   Add to `config/config.php`:
   ```php
   return [
       'security' => [
           'mode' => 'traditional', // Keep traditional mode
           'zeroshot' => [
               'enabled' => false, // Not enforcing yet
               'shadow_mode' => true, // Log decisions for comparison
               'confidence_threshold' => 0.85,
               'logging' => [
                   'log_all_decisions' => true,
                   'log_low_confidence' => true,
               ],
           ],
       ],
   ];
   ```

3. **Monitor Logs**
   
   Watch logs for 1-2 weeks:
   ```bash
   tail -f logs/spicecrm.log | grep ZeroShot
   ```

4. **Analyze Results**
   
   Compare zero-shot decisions with traditional ACL:
   - Agreement rate
   - Confidence distribution
   - Anomaly detections
   - Performance impact

#### Success Criteria:
- [ ] Zero-shot decisions logged for at least 1000 access requests
- [ ] Agreement rate with traditional ACL > 95%
- [ ] No performance degradation
- [ ] Logs reviewed and understood

### Phase 2: Pilot Deployment (Weeks 3-4)

**Objective**: Enable hybrid mode for a small pilot group

#### Steps:

1. **Select Pilot Group**
   - Choose low-risk department (e.g., Sales)
   - 5-10 users
   - Non-critical modules

2. **Configure Hybrid Mode**
   ```php
   'security' => [
       'mode' => 'hybrid',
       'zeroshot' => [
           'enabled' => true,
           'confidence_threshold' => 0.90, // Start with high threshold
           'fallback_to_acl' => true, // Always fallback
           'pilot_users' => ['user1_id', 'user2_id'], // Optional: limit to specific users
       ],
   ],
   ```

3. **Communicate with Pilot Users**
   - Explain the change
   - Ask them to report any access issues
   - Provide feedback mechanism

4. **Monitor Closely**
   - Daily log reviews
   - User feedback
   - Performance metrics
   - Decision confidence distribution

#### Success Criteria:
- [ ] No access issues reported by pilot users
- [ ] Confidence threshold appropriate (not too many fallbacks)
- [ ] Performance acceptable
- [ ] Positive user feedback

### Phase 3: Gradual Rollout (Weeks 5-8)

**Objective**: Expand to more users and modules

#### Steps:

1. **Week 5: Expand to More Departments**
   - Add 2-3 more departments
   - Keep high confidence threshold (0.88-0.90)

2. **Week 6: Expand to More Modules**
   - Include more modules in zero-shot evaluation
   - Monitor sensitive modules carefully

3. **Week 7: Lower Confidence Threshold**
   - Gradually reduce threshold to 0.85
   - Monitor fallback rate
   - Adjust if too many fallbacks

4. **Week 8: Organization-Wide**
   - Enable for all users
   - Keep fallback to ACL enabled

#### Success Criteria:
- [ ] All departments using hybrid mode
- [ ] Fallback rate < 20%
- [ ] No security incidents
- [ ] User satisfaction maintained

### Phase 4: Optimization (Weeks 9-12)

**Objective**: Tune the system and train on organization-specific patterns

#### Steps:

1. **Analyze Decision Patterns**
   - Review logs for patterns
   - Identify common low-confidence scenarios
   - Document organization-specific policies

2. **Add Custom Rules**
   
   Extend `ZeroShotPolicyEngine` with organization-specific rules:
   ```php
   // Example: Custom rule for finance department
   if ($request->userAttributes['department'] === 'Finance' &&
       $request->resourceAttributes['module'] === 'Invoices') {
       $allowed = true;
       $confidence = 0.92;
       $reasoning = 'Finance department has access to Invoices';
       return new AccessDecision($request->requestId, $allowed, $confidence, $reasoning);
   }
   ```

3. **Tune Confidence Thresholds**
   - Adjust based on observed patterns
   - Different thresholds for different modules if needed

4. **Performance Optimization**
   - Enable caching
   - Optimize rule evaluation
   - Database query optimization

#### Success Criteria:
- [ ] Fallback rate < 10%
- [ ] Custom rules cover organization-specific scenarios
- [ ] Performance optimized
- [ ] Security incidents = 0

### Phase 5: Advanced Features (Weeks 13+)

**Objective**: Enable advanced zero-shot features

#### Steps:

1. **ML Integration** (Optional)
   - Integrate with OpenAI or Azure ML
   - Use embeddings for similarity-based decisions
   - Train on organization-specific data

2. **Natural Language Policies** (Optional)
   - Define policies in natural language
   - Automatically translate to rules

3. **Advanced Anomaly Detection**
   - ML-based anomaly detection
   - Integration with SIEM systems
   - Automated alerts

4. **Consider Full Zero-Shot Mode**
   - Evaluate whether to disable fallback
   - Only if confidence is very high (> 99% accuracy)

## Rollback Plan

If issues arise at any phase:

1. **Immediate Rollback**
   ```php
   'security' => ['mode' => 'traditional', 'zeroshot' => ['enabled' => false]]
   ```

2. **Investigate Issue**
   - Review logs
   - Identify root cause
   - Fix or adjust configuration

3. **Resume When Ready**
   - Return to previous phase
   - Apply lessons learned

## Monitoring and Metrics

### Key Metrics to Track:

1. **Decision Metrics**
   - Total decisions per day
   - Zero-shot vs. ACL decisions
   - Fallback rate
   - Confidence score distribution

2. **Accuracy Metrics**
   - Agreement rate with traditional ACL
   - False positives (incorrect denials)
   - False negatives (incorrect allowances)

3. **Performance Metrics**
   - Decision latency
   - System response time
   - Resource utilization

4. **Security Metrics**
   - Anomalies detected
   - Security incidents
   - Unauthorized access attempts

### Monitoring Tools:

1. **Log Analysis**
   ```bash
   # Count decisions per hour
   grep "ZeroShot Decision" spicecrm.log | cut -d' ' -f1-2 | uniq -c
   
   # Average confidence
   grep "confidence" spicecrm.log | awk '{sum+=$NF; count++} END {print sum/count}'
   ```

2. **Dashboard** (Future Enhancement)
   - Real-time decision statistics
   - Confidence distribution charts
   - Anomaly alerts
   - User feedback integration

## Testing Checklist

Before each phase:

- [ ] Test in development environment
- [ ] Test with various user types (admin, regular, portal, API)
- [ ] Test with various modules and actions
- [ ] Test edge cases
- [ ] Test performance under load
- [ ] Verify logging works correctly
- [ ] Verify anomaly detection works
- [ ] Test rollback procedure

## Common Issues and Solutions

### Issue 1: High Fallback Rate

**Symptom**: More than 30% of decisions falling back to ACL

**Solution**:
- Lower confidence threshold
- Add organization-specific rules
- Review and adjust ABAC attributes

### Issue 2: Incorrect Denials

**Symptom**: Users reporting they can't access resources they should have access to

**Solution**:
- Review decision logs
- Identify the rule that caused denial
- Adjust rule or add exception
- Consider adding user feedback mechanism

### Issue 3: Performance Degradation

**Symptom**: Slow response times

**Solution**:
- Enable decision caching
- Optimize rule evaluation order
- Consider async decision logging
- Scale infrastructure if needed

### Issue 4: Too Many Anomaly Alerts

**Symptom**: Alert fatigue from excessive anomaly alerts

**Solution**:
- Adjust anomaly detection thresholds
- Add whitelist for known patterns
- Tune anomaly detection rules

## Post-Migration

After successful migration:

1. **Documentation**
   - Document custom rules
   - Update security policies
   - Train support team

2. **Continuous Improvement**
   - Regular log reviews
   - Quarterly policy reviews
   - User feedback collection
   - Performance monitoring

3. **Compliance**
   - Ensure compliance with regulations
   - Audit trail maintenance
   - Regular security assessments

## Getting Help

- Review documentation: `/docs/zero-shot-security-architecture.md`
- Check logs: `logs/spicecrm.log`
- Contact: security-team@yourcompany.com
- GitHub Issues: Create issue in repository

## Appendix: Configuration Templates

### Development Environment
```php
'security' => [
    'mode' => 'hybrid',
    'zeroshot' => [
        'enabled' => true,
        'confidence_threshold' => 0.80, // Lower for testing
        'fallback_to_acl' => true,
        'logging' => ['log_all_decisions' => true],
    ],
];
```

### Production Environment
```php
'security' => [
    'mode' => 'hybrid',
    'zeroshot' => [
        'enabled' => true,
        'confidence_threshold' => 0.85,
        'fallback_to_acl' => true,
        'anomaly_detection' => true,
        'logging' => [
            'log_all_decisions' => true,
            'alert_on_anomaly' => true,
        ],
    ],
];
```

### High-Security Environment
```php
'security' => [
    'mode' => 'hybrid',
    'zeroshot' => [
        'enabled' => true,
        'confidence_threshold' => 0.95, // Higher threshold
        'fallback_to_acl' => true,
        'anomaly_detection' => true,
        'logging' => [
            'log_all_decisions' => true,
            'alert_on_anomaly' => true,
        ],
        'security' => [
            'force_traditional_acl_modules' => [
                'Administration',
                'SystemSettings',
                'Users',
            ],
        ],
    ],
];
```
