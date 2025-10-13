# ImpressCMS Testing Guide

This document provides comprehensive information about the ImpressCMS testing infrastructure using Pest 4.

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Running Tests](#running-tests)
- [Test Structure](#test-structure)
- [Writing Tests](#writing-tests)
- [Best Practices](#best-practices)
- [Continuous Integration](#continuous-integration)

## Overview

ImpressCMS uses [Pest](https://pestphp.com/) as its testing framework. Pest is a modern PHP testing framework built on top of PHPUnit, providing an elegant and expressive syntax for writing tests.

### Why Pest?

- **Elegant Syntax**: Clean, readable test code using `describe()` and `it()` blocks
- **Modern PHP**: Built for PHP 8.2+ with full type safety
- **Powerful Expectations**: Rich assertion API with `expect()` syntax
- **PHPUnit Compatible**: Built on PHPUnit, so all PHPUnit features are available
- **Fast**: Optimized for speed with parallel test execution support

## Installation

The testing dependencies are already configured in `composer.json`. To install them:

```bash
composer install
```

This will install:
- `pestphp/pest` - The Pest testing framework
- `pestphp/pest-plugin-arch` - Architecture testing plugin

## Running Tests

### Run All Tests

```bash
vendor/bin/pest
```

### Run Specific Test Suites

```bash
# Run only unit tests
vendor/bin/pest --testsuite=Unit

# Run only feature tests
vendor/bin/pest --testsuite=Feature

# Run only integration tests
vendor/bin/pest --testsuite=Integration
```

### Run Specific Test Files

```bash
# Run a specific test file
vendor/bin/pest tests/Unit/Database/Criteria/ItemTest.php

# Run tests in a directory
vendor/bin/pest tests/Unit/Database
```

### Run with Coverage

```bash
# Generate HTML coverage report
vendor/bin/pest --coverage --coverage-html=coverage

# Generate coverage with minimum threshold
vendor/bin/pest --coverage --min=80
```

### Run with Filters

```bash
# Run tests matching a pattern
vendor/bin/pest --filter="render"

# Run tests in parallel (faster)
vendor/bin/pest --parallel
```

## Test Structure

```
tests/
├── bootstrap.php              # Test bootstrap file
├── Pest.php                   # Pest configuration
├── TestCase.php               # Base test case class
├── README.md                  # This file
├── Unit/                      # Unit tests
│   ├── Database/
│   │   ├── Criteria/
│   │   │   ├── ElementTest.php
│   │   │   ├── ItemTest.php
│   │   │   └── CompoTest.php
│   │   ├── ConnectionTest.php
│   │   └── FactoryTest.php
│   └── ...
├── Feature/                   # Feature tests
│   └── ...
└── Integration/               # Integration tests
    ├── Database/
    │   └── CriteriaIntegrationTest.php
    └── ...
```

### Test Types

#### Unit Tests (`tests/Unit/`)
- Test individual classes and methods in isolation
- Fast execution
- No external dependencies (database, filesystem, etc.)
- Mock external dependencies

#### Feature Tests (`tests/Feature/`)
- Test complete features or workflows
- May interact with multiple components
- Can use test databases or mock services

#### Integration Tests (`tests/Integration/`)
- Test how multiple components work together
- Verify correct integration between systems
- May require database or other services

## Writing Tests

### Basic Test Structure

```php
<?php

use Tests\TestCase;

describe('MyClass', function () {
    
    beforeEach(function () {
        // Setup before each test
        $this->instance = new MyClass();
    });
    
    it('does something', function () {
        $result = $this->instance->doSomething();
        expect($result)->toBe('expected value');
    });
    
    it('handles edge cases', function () {
        expect($this->instance->handleEdgeCase())->toBeTrue();
    });
});
```

### Using Expectations

Pest provides a rich set of expectations:

```php
// Equality
expect($value)->toBe(10);
expect($value)->toEqual($expected);

// Types
expect($value)->toBeInt();
expect($value)->toBeString();
expect($value)->toBeArray();
expect($value)->toBeInstanceOf(MyClass::class);

// Truthiness
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeNull();
expect($value)->toBeEmpty();

// Strings
expect($string)->toContain('substring');
expect($string)->toStartWith('prefix');
expect($string)->toEndWith('suffix');

// Arrays
expect($array)->toHaveCount(5);
expect($array)->toContain('value');
expect($array)->toHaveKey('key');

// Numbers
expect($number)->toBeGreaterThan(5);
expect($number)->toBeLessThan(10);
expect($number)->toBeGreaterThanOrEqual(5);

// Custom expectations (defined in Pest.php)
expect($sql)->toBeValidSql();
expect($filter)->toBeValidLdapFilter();
expect($order)->toBeAscOrDesc();
```

### Testing Database Criteria

Example test for criteria classes:

```php
describe('icms_db_criteria_Item', function () {
    it('renders equality condition', function () {
        $item = new icms_db_criteria_Item('username', 'john', '=');
        expect($item->render())->toBe("username = 'john'");
    });
    
    it('renders WHERE clause', function () {
        $item = new icms_db_criteria_Item('status', 'active');
        expect($item->renderWhere())->toBe("WHERE status = 'active'");
    });
});
```

### Helper Functions

Use the helper functions defined in `tests/Pest.php`:

```php
// Create a criteria item
$item = createCriteriaItem('username', 'john', '=');

// Create a criteria composition
$compo = createCriteriaCompo();
```

### Grouping Tests

Use `describe()` blocks to organize related tests:

```php
describe('MyClass', function () {
    
    describe('constructor', function () {
        it('initializes with defaults', function () {
            // Test constructor
        });
    });
    
    describe('myMethod', function () {
        it('handles normal case', function () {
            // Test normal case
        });
        
        it('handles edge case', function () {
            // Test edge case
        });
    });
});
```

## Best Practices

### 1. Follow ImpressCMS Coding Standards

- Use PSR-12 coding style
- Follow ImpressCMS conventions (see `CLAUDE.md`)
- Use camelCase for test methods
- Use descriptive test names

### 2. Write Descriptive Tests

```php
// Good
it('renders WHERE clause with multiple AND conditions', function () {
    // ...
});

// Bad
it('test1', function () {
    // ...
});
```

### 3. Test One Thing Per Test

Each test should verify one specific behavior:

```php
// Good
it('sets sort field', function () {
    $element->setSort('username');
    expect($element->getSort())->toBe('username');
});

it('sets order to DESC', function () {
    $element->setOrder('DESC');
    expect($element->getOrder())->toBe('DESC');
});

// Bad
it('sets sort and order', function () {
    $element->setSort('username');
    $element->setOrder('DESC');
    expect($element->getSort())->toBe('username');
    expect($element->getOrder())->toBe('DESC');
});
```

### 4. Use beforeEach for Setup

```php
describe('MyClass', function () {
    beforeEach(function () {
        $this->instance = new MyClass();
    });
    
    it('uses the instance', function () {
        expect($this->instance)->toBeInstanceOf(MyClass::class);
    });
});
```

### 5. Test Edge Cases

Always test:
- Empty values
- Null values
- Boundary conditions
- Invalid input
- Error conditions

### 6. Keep Tests Fast

- Mock external dependencies
- Avoid database calls in unit tests
- Use in-memory databases for integration tests when possible

### 7. Make Tests Independent

Each test should be able to run independently:
- Don't rely on test execution order
- Clean up after each test
- Don't share state between tests

## Continuous Integration

### GitHub Actions

Add a workflow file (`.github/workflows/tests.yml`):

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo, pdo_mysql
          coverage: xdebug
      
      - name: Install dependencies
        run: composer install --prefer-dist --no-progress
      
      - name: Run tests
        run: vendor/bin/pest --coverage --min=80
```

## Extending the Test Suite

### Adding New Test Files

1. Create a new test file in the appropriate directory
2. Use the Pest syntax with `describe()` and `it()`
3. Follow the naming convention: `*Test.php`

### Adding Custom Expectations

Add custom expectations in `tests/Pest.php`:

```php
expect()->extend('toBeValidEmail', function () {
    $email = $this->value;
    expect(filter_var($email, FILTER_VALIDATE_EMAIL))->not->toBeFalse();
    return $this;
});
```

### Adding Helper Functions

Add helper functions in `tests/Pest.php`:

```php
function createTestUser(string $username = 'test'): User
{
    return new User(['username' => $username]);
}
```

## Troubleshooting

### Tests Not Found

Make sure:
- Test files end with `Test.php`
- Test files are in the correct directory
- Composer autoload is up to date: `composer dump-autoload`

### Class Not Found

Make sure:
- The bootstrap file is loading correctly
- ImpressCMS autoloader is initialized
- Required constants are defined

### Slow Tests

- Use `--parallel` flag for parallel execution
- Profile tests with `--profile`
- Mock external dependencies
- Use in-memory databases

## Resources

- [Pest Documentation](https://pestphp.com/docs)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [ImpressCMS Coding Standards](../CLAUDE.md)

## Support

For questions or issues with the test suite:
- Open an issue on GitHub
- Ask in the ImpressCMS community forums
- Check the Pest documentation

