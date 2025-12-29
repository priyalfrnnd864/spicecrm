# Zero-Shot Security Architecture - Visual Overview

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          SpiceCRM Application                            │
└───────────────────────────────┬─────────────────────────────────────────┘
                                │
                                │ Access Request
                                ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                     HybridSecurityController                             │
│                                                                           │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │ Security Mode Configuration                                      │   │
│  │ • Traditional: Use only SpiceACL                                 │   │
│  │ • Hybrid: Zero-shot + fallback to ACL                           │   │
│  │ • ZeroShot: Use only zero-shot (with optional fallback)        │   │
│  └─────────────────────────────────────────────────────────────────┘   │
└───────────────────────┬──────────────────────┬──────────────────────────┘
                        │                      │
            ┌───────────▼──────────┐  ┌───────▼────────────┐
            │  Zero-Shot Engine    │  │  Traditional ACL   │
            │  (if enabled)        │  │  (SpiceACL)        │
            └───────────┬──────────┘  └───────┬────────────┘
                        │                      │
                        │                      │
                        ▼                      ▼
            ┌─────────────────────┐  ┌──────────────────┐
            │ Confidence >= 0.85? │  │ SpiceACL Rules   │
            └──────┬──────────────┘  └─────────┬────────┘
                   │ Yes│ No                    │
         ┌─────────┴────┴──────────┐           │
         │                          │           │
         ▼                          ▼           ▼
    ┌─────────┐              ┌──────────────────────┐
    │ ALLOWED │              │ Fallback to          │
    │ DENIED  │              │ Traditional ACL      │
    └─────────┘              └──────────────────────┘
```

## Zero-Shot Policy Engine Flow

```
                    ┌──────────────────┐
                    │  Access Request  │
                    │  • User          │
                    │  • Resource      │
                    │  • Action        │
                    │  • Context       │
                    └────────┬─────────┘
                             │
                             ▼
              ┌──────────────────────────┐
              │ Extract Attributes       │
              │ • User: admin, dept, etc │
              │ • Resource: module, etc  │
              │ • Context: time, IP, etc │
              └────────┬─────────────────┘
                       │
                       ▼
        ┌──────────────────────────────────┐
        │      Apply ABAC Rules             │
        │                                   │
        │  Rule 1: Is user admin?          │
        │    ↓ No                           │
        │  Rule 2: Is user API-only?       │
        │    ↓ No                           │
        │  Rule 3: Is user portal-only?    │
        │    ↓ No                           │
        │  Rule 4: Does user own resource? │
        │    ↓ No                           │
        │  Rule 5: Same department?        │
        │    ↓ No                           │
        │  Rule 6: Default read rule?      │
        │    ↓ No                           │
        │  Rule 7: Default deny            │
        │                                   │
        └────────┬─────────────────────────┘
                 │
                 ▼
    ┌────────────────────────────┐
    │   Generate Decision        │
    │   • Allowed: true/false    │
    │   • Confidence: 0.0 - 1.0  │
    │   • Reasoning: "..."       │
    │   • Source: rule name      │
    └────────┬───────────────────┘
             │
             ├──────────────────┐
             │                  │
             ▼                  ▼
    ┌─────────────────┐  ┌─────────────────┐
    │ Anomaly Check   │  │ Decision Logger │
    │ • Unusual time? │  │ • Log to file   │
    │ • Sensitive     │  │ • Alert on      │
    │   action?       │  │   anomaly       │
    └─────────────────┘  └─────────────────┘
                             │
                             ▼
                    ┌────────────────┐
                    │ Return Decision│
                    └────────────────┘
```

## Confidence Scoring System

```
Confidence Level           Decision Making
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
1.0 (100%)  ████████████  Absolute certainty → Use zero-shot
0.95 (95%)  ███████████░  Very high → Use zero-shot
0.90 (90%)  ██████████░░  High → Use zero-shot
0.85 (85%)  █████████░░░  Threshold → Use zero-shot
0.80 (80%)  ████████░░░░  Medium-high → Fallback to ACL
0.75 (75%)  ███████░░░░░  Medium → Fallback to ACL
0.70 (70%)  ██████░░░░░░  Medium-low → Fallback to ACL
0.60 (60%)  █████░░░░░░░  Low → Fallback to ACL
```

## Attribute-Based Access Control (ABAC) Model

```
┌─────────────────────────────────────────────────────────────┐
│                     Access Decision                          │
│                                                              │
│  Based on combination of:                                   │
│                                                              │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐  │
│  │ User          │  │ Resource      │  │ Context       │  │
│  │ Attributes    │  │ Attributes    │  │ Attributes    │  │
│  ├───────────────┤  ├───────────────┤  ├───────────────┤  │
│  │• ID           │  │• Module       │  │• Time         │  │
│  │• Username     │  │• ID           │  │• Day of week  │  │
│  │• Is Admin     │  │• Assigned to  │  │• IP address   │  │
│  │• Department   │  │• Created by   │  │• User agent   │  │
│  │• Title        │  │• Department   │  │• Device type  │  │
│  │• Portal only  │  │• Sensitivity  │  │• Network      │  │
│  │• API user     │  │• Deleted      │  │• Location     │  │
│  └───────────────┘  └───────────────┘  └───────────────┘  │
│         │                   │                   │           │
│         └───────────────────┴───────────────────┘           │
│                             │                               │
│                             ▼                               │
│                  ┌─────────────────────┐                   │
│                  │  Policy Evaluation  │                   │
│                  │  (ABAC Rules)       │                   │
│                  └─────────────────────┘                   │
│                             │                               │
│                             ▼                               │
│                  ┌─────────────────────┐                   │
│                  │ Access Decision     │                   │
│                  │ + Confidence Score  │                   │
│                  └─────────────────────┘                   │
└─────────────────────────────────────────────────────────────┘
```

## Migration Phases Timeline

```
Phase 1: Shadow Mode
├─ Week 1-2: Log decisions without enforcing
└─ Output: Decision logs, agreement rate analysis

Phase 2: Pilot Deployment  
├─ Week 3-4: Enable hybrid for 5-10 users
└─ Output: User feedback, performance metrics

Phase 3: Gradual Rollout
├─ Week 5: Add 2-3 departments
├─ Week 6: Expand to more modules
├─ Week 7: Lower confidence threshold
└─ Week 8: Organization-wide deployment

Phase 4: Optimization
├─ Week 9-10: Analyze patterns, add custom rules
└─ Week 11-12: Tune thresholds, optimize performance

Phase 5: Advanced Features (Optional)
└─ Week 13+: ML integration, NL policies, advanced anomaly detection
```

## Comparison: Traditional vs Zero-Shot

```
┌────────────────────┬──────────────────────┬─────────────────────┐
│ Aspect             │ Traditional ACL      │ Zero-Shot           │
├────────────────────┼──────────────────────┼─────────────────────┤
│ Configuration      │ Manual for each role │ Minimal setup       │
│ New Modules        │ Requires setup       │ Automatic inference │
│ Context Awareness  │ Limited              │ Time, location, etc │
│ Adaptability       │ Static               │ Dynamic             │
│ Explainability     │ Rule-based           │ AI + reasoning      │
│ Maintenance        │ High overhead        │ Self-improving      │
│ Performance        │ Fast (DB lookup)     │ Fast (with caching) │
│ Complexity         │ Simple concept       │ More complex        │
│ Trust              │ High (known rules)   │ Builds over time    │
│ Anomaly Detection  │ None                 │ Built-in            │
└────────────────────┴──────────────────────┴─────────────────────┘
```

## Decision Logging and Monitoring

```
┌─────────────────────────────────────────────────────────────┐
│                   Decision Logger                            │
│                                                              │
│  Every access decision is logged with:                      │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │ • Decision ID                                      │    │
│  │ • Request ID                                       │    │
│  │ • User information                                 │    │
│  │ • Resource details                                 │    │
│  │ • Action performed                                 │    │
│  │ • Decision (allow/deny)                            │    │
│  │ • Confidence score                                 │    │
│  │ • Reasoning                                        │    │
│  │ • Source (zero-shot vs ACL)                        │    │
│  │ • Anomaly flag                                     │    │
│  │ • Timestamp                                        │    │
│  │ • Metadata                                         │    │
│  └────────────────────────────────────────────────────┘    │
│                          │                                  │
│          ┌───────────────┼───────────────┐                 │
│          │               │               │                 │
│          ▼               ▼               ▼                 │
│  ┌────────────┐  ┌────────────┐  ┌────────────┐          │
│  │ System Log │  │  Database  │  │   Alerts   │          │
│  │   (File)   │  │  (Future)  │  │  (Email/   │          │
│  │            │  │            │  │   Slack)   │          │
│  └────────────┘  └────────────┘  └────────────┘          │
└─────────────────────────────────────────────────────────────┘
```

## Use Case Example: Sales Manager Access

```
Scenario: Sales Manager trying to view Account record
─────────────────────────────────────────────────────

User: Sarah (Sales Manager)
├─ Attributes: {department: "Sales", is_admin: false}
└─ Action: View

Resource: Account #12345
├─ Attributes: {module: "Accounts", department: "Sales"}
└─ Assigned to: John (also in Sales)

Context:
├─ Time: 2:30 PM (business hours)
├─ Location: Office IP
└─ Device: Trusted laptop

Zero-Shot Evaluation:
├─ Rule 1 (Admin): No
├─ Rule 2 (API user): No
├─ Rule 3 (Portal): No
├─ Rule 4 (Owner): No (not assigned to Sarah)
├─ Rule 5 (Department): Yes! ✓
│   └─ Same department (Sales)
└─ Decision: ALLOW with confidence 0.82

Result: Access granted by zero-shot engine
Reasoning: "User and resource are in the same department (read access)"
```

## Key Takeaways

1. **Hybrid Approach** = Safety + Innovation
   - Zero-shot for high-confidence decisions
   - Traditional ACL as safety net

2. **ABAC** = More Flexible than RBAC
   - Dynamic attribute evaluation
   - Context-aware decisions

3. **Confidence Scoring** = Trust Gradual Building
   - Start with high threshold (0.90)
   - Gradually lower as system proves reliable

4. **Phased Migration** = Risk Mitigation
   - Shadow mode → Pilot → Rollout → Optimize
   - Can rollback at any phase

5. **Monitoring** = Continuous Improvement
   - Log all decisions
   - Track metrics
   - Learn from patterns
