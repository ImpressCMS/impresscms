<?php
/**
 * Multisite Dispatcher Test Script
 * 
 * This script tests the multisite dispatcher functionality without requiring
 * a full ImpressCMS installation.
 *
 * Usage: php test-multisite-dispatcher.php
 *
 * @package Core
 * @since ImpressCMS 2.0
 */

// Test the sanitization function
function icms_sanitize_host($host)
{
    // Remove port number if present
    $host = preg_replace('/:[0-9]+$/', '', $host);
    
    // Convert to lowercase
    $host = strtolower($host);
    
    // Prevent directory traversal attempts - replace consecutive dots with single dot
    while (strpos($host, '..') !== false) {
        $host = str_replace('..', '.', $host);
    }
    
    // Remove any characters that aren't alphanumeric, dash, or dot
    $host = preg_replace('/[^a-z0-9\.\-]/', '', $host);
    
    // Remove leading/trailing dots and dashes
    $host = trim($host, '.-');
    
    return $host;
}

// Test cases
$test_cases = [
    // Valid hostnames
    ['input' => 'example.com', 'expected' => 'example.com', 'description' => 'Simple domain'],
    ['input' => 'www.example.com', 'expected' => 'www.example.com', 'description' => 'Domain with www'],
    ['input' => 'subdomain.example.com', 'expected' => 'subdomain.example.com', 'description' => 'Subdomain'],
    ['input' => 'SUB-DOMAIN.EXAMPLE.COM', 'expected' => 'sub-domain.example.com', 'description' => 'Uppercase with dash'],
    ['input' => 'site123.example.com', 'expected' => 'site123.example.com', 'description' => 'Alphanumeric subdomain'],
    
    // With port numbers
    ['input' => 'example.com:8080', 'expected' => 'example.com', 'description' => 'Domain with port'],
    ['input' => 'localhost:3000', 'expected' => 'localhost', 'description' => 'Localhost with port'],
    
    // Security tests - directory traversal
    ['input' => '../../../etc/passwd', 'expected' => 'etcpasswd', 'description' => 'Directory traversal attempt'],
    ['input' => 'evil..com', 'expected' => 'evil.com', 'description' => 'Double dot in domain'],
    ['input' => '..example.com', 'expected' => 'example.com', 'description' => 'Leading double dots'],
    ['input' => 'example.com..', 'expected' => 'example.com', 'description' => 'Trailing double dots'],
    
    // Security tests - special characters
    ['input' => 'example.com/malicious', 'expected' => 'example.commalicious', 'description' => 'Domain with slash'],
    ['input' => 'example.com?query=bad', 'expected' => 'example.comquerybad', 'description' => 'Domain with query string'],
    ['input' => 'example.com#fragment', 'expected' => 'example.comfragment', 'description' => 'Domain with fragment'],
    ['input' => 'user@example.com', 'expected' => 'userexample.com', 'description' => 'Domain with @ symbol'],
    ['input' => 'example.com;ls', 'expected' => 'example.comls', 'description' => 'Command injection attempt'],
    ['input' => 'example.com`whoami`', 'expected' => 'example.comwhoami', 'description' => 'Backtick command attempt'],
    ['input' => 'example.com$(id)', 'expected' => 'example.comid', 'description' => 'Command substitution attempt'],
    
    // Edge cases
    ['input' => '', 'expected' => '', 'description' => 'Empty string'],
    ['input' => '...', 'expected' => '', 'description' => 'Only dots'],
    ['input' => '---', 'expected' => '', 'description' => 'Only dashes'],
    ['input' => '.example.com', 'expected' => 'example.com', 'description' => 'Leading dot'],
    ['input' => 'example.com.', 'expected' => 'example.com', 'description' => 'Trailing dot'],
    ['input' => '-example.com', 'expected' => 'example.com', 'description' => 'Leading dash'],
    ['input' => 'example.com-', 'expected' => 'example.com', 'description' => 'Trailing dash'],
];

// Run tests
echo "===========================================\n";
echo "Multisite Dispatcher Sanitization Tests\n";
echo "===========================================\n\n";

$passed = 0;
$failed = 0;

foreach ($test_cases as $test) {
    $result = icms_sanitize_host($test['input']);
    $status = ($result === $test['expected']) ? '✓ PASS' : '✗ FAIL';
    
    if ($result === $test['expected']) {
        $passed++;
    } else {
        $failed++;
    }
    
    echo sprintf(
        "[%s] %s\n  Input:    '%s'\n  Expected: '%s'\n  Got:      '%s'\n\n",
        $status,
        $test['description'],
        $test['input'],
        $test['expected'],
        $result
    );
}

// Summary
echo "===========================================\n";
echo "Test Summary\n";
echo "===========================================\n";
echo sprintf("Total tests: %d\n", count($test_cases));
echo sprintf("Passed: %d (%.1f%%)\n", $passed, ($passed / count($test_cases)) * 100);
echo sprintf("Failed: %d (%.1f%%)\n", $failed, ($failed / count($test_cases)) * 100);
echo "===========================================\n";

// Exit with appropriate code
exit($failed > 0 ? 1 : 0);
