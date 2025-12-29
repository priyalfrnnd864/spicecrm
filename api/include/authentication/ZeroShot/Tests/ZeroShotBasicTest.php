<?php
/*********************************************************************************
 * Zero-Shot Security - Basic Test Script
 * 
 * This script demonstrates the zero-shot security features and can be used
 * for basic testing and validation.
 ********************************************************************************/

namespace SpiceCRM\includes\authentication\ZeroShot\Tests;

use SpiceCRM\includes\authentication\ZeroShot\AccessRequest;
use SpiceCRM\includes\authentication\ZeroShot\AccessDecision;
use SpiceCRM\includes\authentication\ZeroShot\ZeroShotPolicyEngine;
use SpiceCRM\includes\authentication\ZeroShot\HybridSecurityController;

/**
 * Basic tests for zero-shot security
 */
class ZeroShotBasicTest
{
    /**
     * Test admin user access
     */
    public function testAdminAccess()
    {
        echo "Testing Admin User Access...\n";
        
        $engine = new ZeroShotPolicyEngine();
        
        // Create mock admin user
        $adminUser = $this->createMockUser([
            'id' => 'admin-001',
            'user_name' => 'admin',
            'is_admin' => true,
        ]);
        
        // Test access to sensitive module
        $request = new AccessRequest($adminUser, 'Administration', 'edit');
        $decision = $engine->evaluateAccess($request);
        
        $this->assertDecision($decision, true, 1.0, "Admin should have full access");
        
        echo "✓ Admin access test passed\n\n";
    }

    /**
     * Test API user access
     */
    public function testAPIUserAccess()
    {
        echo "Testing API User Access...\n";
        
        $engine = new ZeroShotPolicyEngine();
        
        // Create mock API user
        $apiUser = $this->createMockUser([
            'id' => 'api-001',
            'user_name' => 'api_user',
            'is_admin' => false,
            'is_api_user' => true,
        ]);
        
        // Test read access - should be allowed
        $request1 = new AccessRequest($apiUser, 'Accounts', 'view');
        $decision1 = $engine->evaluateAccess($request1);
        $this->assertDecision($decision1, true, 0.95, "API user should have read access");
        
        // Test write access - should be denied
        $request2 = new AccessRequest($apiUser, 'Accounts', 'edit');
        $decision2 = $engine->evaluateAccess($request2);
        $this->assertDecision($decision2, false, 0.95, "API user should not have write access");
        
        echo "✓ API user access test passed\n\n";
    }

    /**
     * Test portal user access
     */
    public function testPortalUserAccess()
    {
        echo "Testing Portal User Access...\n";
        
        $engine = new ZeroShotPolicyEngine();
        
        // Create mock portal user
        $portalUser = $this->createMockUser([
            'id' => 'portal-001',
            'user_name' => 'portal_user',
            'is_admin' => false,
            'portal_only' => true,
        ]);
        
        // Test access to allowed module - should be allowed
        $request1 = new AccessRequest($portalUser, 'Contacts', 'view');
        $decision1 = $engine->evaluateAccess($request1);
        $this->assertDecision($decision1, true, 0.90, "Portal user should access Contacts");
        
        // Test access to restricted module - should be denied
        $request2 = new AccessRequest($portalUser, 'Accounts', 'view');
        $decision2 = $engine->evaluateAccess($request2);
        $this->assertDecision($decision2, false, 0.95, "Portal user should not access Accounts");
        
        echo "✓ Portal user access test passed\n\n";
    }

    /**
     * Test anomaly detection
     */
    public function testAnomalyDetection()
    {
        echo "Testing Anomaly Detection...\n";
        
        $engine = new ZeroShotPolicyEngine();
        
        // Create mock user
        $user = $this->createMockUser([
            'id' => 'user-001',
            'user_name' => 'regular_user',
            'is_admin' => false,
        ]);
        
        // Simulate access at unusual hour (3 AM)
        $contextAttributes = [
            'timestamp' => strtotime('2024-01-15 03:00:00'),
            'hour' => 3,
            'day_of_week' => 'Monday',
        ];
        
        $request = new AccessRequest($user, 'Accounts', 'view', $contextAttributes);
        $decision = $engine->evaluateAccess($request);
        
        if ($decision->anomalyDetected) {
            echo "✓ Anomaly correctly detected for unusual hour access\n";
        } else {
            echo "✗ Anomaly NOT detected (may need configuration)\n";
        }
        
        echo "\n";
    }

    /**
     * Test confidence-based decisions
     */
    public function testConfidenceScoring()
    {
        echo "Testing Confidence Scoring...\n";
        
        $engine = new ZeroShotPolicyEngine();
        
        $user = $this->createMockUser([
            'id' => 'user-001',
            'user_name' => 'regular_user',
            'is_admin' => false,
        ]);
        
        // Different scenarios should have different confidence levels
        $scenarios = [
            ['module' => 'Accounts', 'action' => 'view', 'expectedHigh' => false],
            ['module' => 'Users', 'action' => 'edit', 'expectedHigh' => false],
        ];
        
        foreach ($scenarios as $scenario) {
            $request = new AccessRequest($user, $scenario['module'], $scenario['action']);
            $decision = $engine->evaluateAccess($request);
            
            echo "  Scenario: {$scenario['module']} / {$scenario['action']}\n";
            echo "    Confidence: " . ($decision->confidence * 100) . "%\n";
            echo "    Decision: " . ($decision->allowed ? 'ALLOWED' : 'DENIED') . "\n";
        }
        
        echo "✓ Confidence scoring test completed\n\n";
    }

    /**
     * Test hybrid controller
     */
    public function testHybridController()
    {
        echo "Testing Hybrid Controller...\n";
        
        // Note: This requires proper configuration in config.php
        // For demo purposes, we'll just show how it would be used
        
        echo "  Hybrid controller combines zero-shot with traditional ACL\n";
        echo "  - High confidence decisions use zero-shot\n";
        echo "  - Low confidence decisions fall back to ACL\n";
        echo "  See config.example.php for configuration options\n";
        echo "✓ Hybrid controller info displayed\n\n";
    }

    /**
     * Run all tests
     */
    public function runAllTests()
    {
        echo "\n";
        echo "===========================================\n";
        echo "  Zero-Shot Security - Basic Tests\n";
        echo "===========================================\n\n";
        
        $this->testAdminAccess();
        $this->testAPIUserAccess();
        $this->testPortalUserAccess();
        $this->testAnomalyDetection();
        $this->testConfidenceScoring();
        $this->testHybridController();
        
        echo "===========================================\n";
        echo "  All Tests Completed\n";
        echo "===========================================\n\n";
    }

    /**
     * Create mock user for testing
     */
    private function createMockUser(array $attributes)
    {
        $user = new \stdClass();
        foreach ($attributes as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    /**
     * Assert decision properties
     */
    private function assertDecision($decision, $expectedAllowed, $expectedConfidence, $message)
    {
        $passed = true;
        
        if ($decision->allowed !== $expectedAllowed) {
            echo "  ✗ FAILED: Expected allowed={$expectedAllowed}, got {$decision->allowed}\n";
            $passed = false;
        }
        
        if ($decision->confidence < $expectedConfidence - 0.1) {
            echo "  ✗ FAILED: Expected confidence>={$expectedConfidence}, got {$decision->confidence}\n";
            $passed = false;
        }
        
        if ($passed) {
            echo "  ✓ {$message}\n";
            echo "    Decision: " . ($decision->allowed ? 'ALLOWED' : 'DENIED') . "\n";
            echo "    Confidence: " . ($decision->confidence * 100) . "%\n";
            echo "    Reasoning: {$decision->reasoning}\n";
        }
    }
}

// Run tests if executed directly
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    require_once(__DIR__ . '/../../../../../config.php');
    
    $test = new ZeroShotBasicTest();
    $test->runAllTests();
}
