<?php

/**
 * Tests for icms_db_criteria_Item
 *
 * @copyright	https://www.impresscms.org/ The ImpressCMS Project
 * @license		MIT
 * @category	ICMS
 * @package		Tests
 * @subpackage	Database
 */

use Tests\TestCase;

describe('icms_db_criteria_Item', function () {

    describe('constructor', function () {
        it('creates item with column and value', function () {
            $item = new icms_db_criteria_Item('username', 'john');
            expect($item)->toBeInstanceOf(icms_db_criteria_Item::class);
        });

        it('creates item with column, value, and operator', function () {
            $item = new icms_db_criteria_Item('age', '18', '>');
            expect($item)->toBeInstanceOf(icms_db_criteria_Item::class);
        });

        it('creates item with all parameters', function () {
            $item = new icms_db_criteria_Item('username', 'john', '=', 'users', 'LOWER(%s)');
            expect($item)->toBeInstanceOf(icms_db_criteria_Item::class);
        });

        it('inherits from icms_db_criteria_Element', function () {
            $item = new icms_db_criteria_Item('id', '1');
            expect($item)->toBeInstanceOf(icms_db_criteria_Element::class);
        });
    });

    describe('render method', function () {
        describe('basic operators', function () {
            it('renders equality condition', function () {
                $item = new icms_db_criteria_Item('username', 'john', '=');
                expect($item->render())->toBe("username = 'john'");
            });

            it('renders not equal condition', function () {
                $item = new icms_db_criteria_Item('status', 'inactive', '!=');
                expect($item->render())->toBe("status != 'inactive'");
            });

            it('renders greater than condition', function () {
                $item = new icms_db_criteria_Item('age', '18', '>');
                expect($item->render())->toBe("age > '18'");
            });

            it('renders less than condition', function () {
                $item = new icms_db_criteria_Item('price', '100', '<');
                expect($item->render())->toBe("price < '100'");
            });

            it('renders greater than or equal condition', function () {
                $item = new icms_db_criteria_Item('score', '50', '>=');
                expect($item->render())->toBe("score >= '50'");
            });

            it('renders less than or equal condition', function () {
                $item = new icms_db_criteria_Item('quantity', '10', '<=');
                expect($item->render())->toBe("quantity <= '10'");
            });

            it('renders LIKE condition', function () {
                $item = new icms_db_criteria_Item('name', '%john%', 'LIKE');
                expect($item->render())->toBe("name LIKE '%john%'");
            });
        });

        describe('NULL operators', function () {
            it('renders IS NULL condition', function () {
                $item = new icms_db_criteria_Item('deleted_at', '', 'IS NULL');
                expect($item->render())->toBe('deleted_at IS NULL');
            });

            it('renders IS NOT NULL condition', function () {
                $item = new icms_db_criteria_Item('email', '', 'IS NOT NULL');
                expect($item->render())->toBe('email IS NOT NULL');
            });

            it('renders is null with lowercase', function () {
                $item = new icms_db_criteria_Item('field', '', 'is null');
                expect($item->render())->toBe('field IS NULL');
            });
        });

        describe('IN operator', function () {
            it('renders IN condition', function () {
                $item = new icms_db_criteria_Item('id', '(1,2,3)', 'IN');
                expect($item->render())->toBe('id IN (1,2,3)');
            });

            it('renders NOT IN condition', function () {
                $item = new icms_db_criteria_Item('status', '(0,5)', 'NOT IN');
                expect($item->render())->toBe('status NOT IN (0,5)');
            });
        });

        describe('with prefix', function () {
            it('renders with table prefix', function () {
                $item = new icms_db_criteria_Item('username', 'john', '=', 'users');
                expect($item->render())->toBe("users.username = 'john'");
            });

            it('renders with prefix and different operator', function () {
                $item = new icms_db_criteria_Item('age', '18', '>', 'members');
                expect($item->render())->toBe("members.age > '18'");
            });
        });

        describe('with function', function () {
            it('renders with LOWER function', function () {
                $item = new icms_db_criteria_Item('email', 'test@example.com', '=', '', 'LOWER(%s)');
                expect($item->render())->toBe("LOWER(email) = 'test@example.com'");
            });

            it('renders with UPPER function', function () {
                $item = new icms_db_criteria_Item('code', 'ABC', '=', '', 'UPPER(%s)');
                expect($item->render())->toBe("UPPER(code) = 'ABC'");
            });

            it('renders with COUNT function', function () {
                $item = new icms_db_criteria_Item('id', '5', '>', '', 'COUNT(%s)');
                expect($item->render())->toBe("COUNT(id) > '5'");
            });
        });

        describe('with prefix and function', function () {
            it('renders with both prefix and function', function () {
                $item = new icms_db_criteria_Item('username', 'john', '=', 'users', 'LOWER(%s)');
                expect($item->render())->toBe("LOWER(users.username) = 'john'");
            });
        });

        describe('backtick handling', function () {
            it('preserves backticks for column references', function () {
                $item = new icms_db_criteria_Item('field', '`other_field`', '=');
                $result = $item->render();
                expect($result)->toContain('`other_field`');
            });

            it('validates backtick content', function () {
                $item = new icms_db_criteria_Item('field', '`invalid; DROP TABLE`', '=');
                $result = $item->render();
                // Should sanitize invalid backtick content
                expect($result)->toContain('``');
            });
        });

        describe('empty value handling', function () {
            it('returns empty string for empty value with regular operator', function () {
                $item = new icms_db_criteria_Item('field', '', '=');
                expect($item->render())->toBe('');
            });

            it('returns empty string for whitespace-only value', function () {
                $item = new icms_db_criteria_Item('field', '   ', '=');
                expect($item->render())->toBe('');
            });

            it('allows empty value with IS NULL operator', function () {
                $item = new icms_db_criteria_Item('field', '', 'IS NULL');
                expect($item->render())->toBe('field IS NULL');
            });
        });

        describe('special characters', function () {
            it('handles single quotes in value', function () {
                $item = new icms_db_criteria_Item('name', "O'Brien", '=');
                $result = $item->render();
                expect($result)->toContain("O'Brien");
            });

            it('handles numeric values', function () {
                $item = new icms_db_criteria_Item('id', '123', '=');
                expect($item->render())->toBe("id = '123'");
            });
        });
    });

    describe('renderWhere method', function () {
        it('renders WHERE clause for valid condition', function () {
            $item = new icms_db_criteria_Item('username', 'john', '=');
            expect($item->renderWhere())->toBe("WHERE username = 'john'");
        });

        it('returns empty string for empty condition', function () {
            $item = new icms_db_criteria_Item('field', '', '=');
            expect($item->renderWhere())->toBe('');
        });

        it('renders WHERE with complex condition', function () {
            $item = new icms_db_criteria_Item('age', '18', '>=', 'users');
            expect($item->renderWhere())->toBe("WHERE users.age >= '18'");
        });
    });

    describe('renderLdap method', function () {
        it('renders basic LDAP filter', function () {
            $item = new icms_db_criteria_Item('uid', '1000', '=');
            expect($item->renderLdap())->toBeValidLdapFilter();
            expect($item->renderLdap())->toBe('(uid=1000)');
        });

        it('converts greater than to greater than or equal for LDAP', function () {
            $item = new icms_db_criteria_Item('age', '18', '>');
            expect($item->renderLdap())->toBe('(age>=18)');
        });

        it('converts less than to less than or equal for LDAP', function () {
            $item = new icms_db_criteria_Item('age', '65', '<');
            expect($item->renderLdap())->toBe('(age<=65)');
        });

        it('converts not equal operator to negated filter', function () {
            $item = new icms_db_criteria_Item('status', 'inactive', '!=');
            expect($item->renderLdap())->toBe('(!(status=inactive))');
        });

        it('converts angle bracket not equal to negated filter', function () {
            $item = new icms_db_criteria_Item('type', 'admin', '<>');
            expect($item->renderLdap())->toBe('(!(type=admin))');
        });

        it('handles IN operator for LDAP', function () {
            $item = new icms_db_criteria_Item('uid', '(1,2,3)', 'IN');
            $result = $item->renderLdap();
            expect($result)->toBeValidLdapFilter();
            expect($result)->toBe('(|(uid=1)(uid=2)(uid=3))');
        });

        it('handles greater than or equal operator', function () {
            $item = new icms_db_criteria_Item('age', '21', '>=');
            expect($item->renderLdap())->toBe('(age>=21)');
        });

        it('handles less than or equal operator', function () {
            $item = new icms_db_criteria_Item('age', '65', '<=');
            expect($item->renderLdap())->toBe('(age<=65)');
        });
    });

    describe('inherited properties', function () {
        it('can set sort on criteria item', function () {
            $item = new icms_db_criteria_Item('username', 'john');
            $item->setSort('created_date');
            expect($item->getSort())->toBe('created_date');
        });

        it('can set limit on criteria item', function () {
            $item = new icms_db_criteria_Item('status', 'active');
            $item->setLimit(10);
            expect($item->getLimit())->toBe(10);
        });
    });
});

