<?php

/**
 * ImpressCMS Base Test Case
 *
 * Base test case class for all ImpressCMS tests.
 * Provides common functionality and setup for testing ImpressCMS components.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Tests
 * @since		1.5
 */

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup can be added here
        // For example, resetting static state, clearing caches, etc.
    }

    /**
     * Clean up the test environment
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up any test artifacts
    }

    /**
     * Assert that a string contains valid SQL
     *
     * @param string $sql
     * @param string $message
     * @return void
     */
    protected function assertValidSql(string $sql, string $message = ''): void
    {
        $this->assertNotEmpty($sql, $message ?: 'SQL should not be empty');
        $this->assertIsString($sql, $message ?: 'SQL should be a string');
    }

    /**
     * Assert that a string is a valid LDAP filter
     *
     * @param string $filter
     * @param string $message
     * @return void
     */
    protected function assertValidLdapFilter(string $filter, string $message = ''): void
    {
        $this->assertStringStartsWith('(', $filter, $message ?: 'LDAP filter should start with (');
        $this->assertStringEndsWith(')', $filter, $message ?: 'LDAP filter should end with )');
    }

    /**
     * Create a mock PDO instance for testing
     *
     * @return \PDO|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockPdo()
    {
        return $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Create a mock database connection for testing
     *
     * @return icms_db_Connection|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockConnection()
    {
        return $this->getMockBuilder(icms_db_Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
