<?php

/**
 * ImpressCMS Pest Configuration
 *
 * This file configures Pest for testing ImpressCMS components.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Tests
 * @since		1.5
 */

/*
|--------------------------------------------------------------------------
| Test Case Binding
|--------------------------------------------------------------------------
|
| Bind test directories to appropriate test case classes.
| Unit tests use the base ImpressCMS test case.
| Feature and Integration tests may require database access.
|
*/

pest()
    ->extend(Tests\TestCase::class)
    ->in('Unit', 'Feature', 'Integration');

/*
|--------------------------------------------------------------------------
| Custom Expectations
|--------------------------------------------------------------------------
|
| Define custom expectations specific to ImpressCMS testing.
|
*/

expect()->extend('toBeValidSql', function () {
    $sql = $this->value;

    // Basic SQL validation - should not be empty and should contain valid SQL keywords
    expect($sql)->not->toBeEmpty();

    return $this;
});

expect()->extend('toBeValidLdapFilter', function () {
    $filter = $this->value;

    // Basic LDAP filter validation - should start and end with parentheses
    expect($filter)->toStartWith('(');
    expect($filter)->toEndWith(')');

    return $this;
});

expect()->extend('toBeAscOrDesc', function () {
    $order = $this->value;
    expect($order)->toBeIn(['ASC', 'DESC']);

    return $this;
});

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
|
| Global helper functions for ImpressCMS tests.
|
*/

/**
 * Create a mock criteria item for testing
 *
 * @param string $column
 * @param mixed $value
 * @param string $operator
 * @return icms_db_criteria_Item
 */
function createCriteriaItem(string $column = 'test_column', $value = 'test_value', string $operator = '='): icms_db_criteria_Item
{
    return new icms_db_criteria_Item($column, $value, $operator);
}

/**
 * Create a mock criteria composition for testing
 *
 * @param icms_db_criteria_Element|null $element
 * @param string $condition
 * @return icms_db_criteria_Compo
 */
function createCriteriaCompo(?icms_db_criteria_Element $element = null, string $condition = 'AND'): icms_db_criteria_Compo
{
    return new icms_db_criteria_Compo($element, $condition);
}
