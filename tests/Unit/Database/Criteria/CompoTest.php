<?php

/**
 * Tests for icms_db_criteria_Compo
 *
 * @copyright	https://www.impresscms.org/ The ImpressCMS Project
 * @license		MIT
 * @category	ICMS
 * @package		Tests
 * @subpackage	Database
 */

use Tests\TestCase;

describe('icms_db_criteria_Compo', function () {

    describe('constructor', function () {
        it('creates empty composition', function () {
            $compo = new icms_db_criteria_Compo();
            expect($compo)->toBeInstanceOf(icms_db_criteria_Compo::class);
            expect($compo->criteriaElements)->toBeArray();
            expect($compo->criteriaElements)->toBeEmpty();
        });

        it('creates composition with initial element', function () {
            $item = new icms_db_criteria_Item('username', 'john');
            $compo = new icms_db_criteria_Compo($item);
            expect($compo->criteriaElements)->toHaveCount(1);
        });

        it('creates composition with initial element and condition', function () {
            $item = new icms_db_criteria_Item('age', '18', '>');
            $compo = new icms_db_criteria_Compo($item, 'OR');
            expect($compo->criteriaElements)->toHaveCount(1);
            expect($compo->conditions)->toContain('OR');
        });

        it('inherits from icms_db_criteria_Element', function () {
            $compo = new icms_db_criteria_Compo();
            expect($compo)->toBeInstanceOf(icms_db_criteria_Element::class);
        });
    });

    describe('add method', function () {
        beforeEach(function () {
            $this->compo = new icms_db_criteria_Compo();
        });

        it('adds a criteria item', function () {
            $item = new icms_db_criteria_Item('username', 'john');
            $this->compo->add($item);
            expect($this->compo->criteriaElements)->toHaveCount(1);
        });

        it('adds multiple criteria items', function () {
            $item1 = new icms_db_criteria_Item('username', 'john');
            $item2 = new icms_db_criteria_Item('age', '18', '>');

            $this->compo->add($item1);
            $this->compo->add($item2);

            expect($this->compo->criteriaElements)->toHaveCount(2);
        });

        it('adds with AND condition by default', function () {
            $item = new icms_db_criteria_Item('status', 'active');
            $this->compo->add($item);
            expect($this->compo->conditions)->toContain('AND');
        });

        it('adds with OR condition', function () {
            $item = new icms_db_criteria_Item('role', 'admin');
            $this->compo->add($item, 'OR');
            expect($this->compo->conditions)->toContain('OR');
        });

        it('returns reference to itself for chaining', function () {
            $item1 = new icms_db_criteria_Item('field1', 'value1');
            $item2 = new icms_db_criteria_Item('field2', 'value2');

            $result = $this->compo->add($item1)->add($item2);

            expect($result)->toBe($this->compo);
            expect($this->compo->criteriaElements)->toHaveCount(2);
        });

        it('can add nested compositions', function () {
            $subCompo = new icms_db_criteria_Compo();
            $subCompo->add(new icms_db_criteria_Item('age', '18', '>'));

            $this->compo->add($subCompo);
            expect($this->compo->criteriaElements)->toHaveCount(1);
            expect($this->compo->criteriaElements[0])->toBeInstanceOf(icms_db_criteria_Compo::class);
        });
    });

    describe('render method', function () {
        it('returns empty string for empty composition', function () {
            $compo = new icms_db_criteria_Compo();
            expect($compo->render())->toBe('');
        });

        it('renders single criteria item', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('username', 'john'));
            expect($compo->render())->toBe("(username = 'john')");
        });

        it('renders two items with AND', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('username', 'john'));
            $compo->add(new icms_db_criteria_Item('status', 'active'), 'AND');

            expect($compo->render())->toBe("(username = 'john' AND status = 'active')");
        });

        it('renders two items with OR', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('role', 'admin'));
            $compo->add(new icms_db_criteria_Item('role', 'moderator'), 'OR');

            expect($compo->render())->toBe("(role = 'admin' OR role = 'moderator')");
        });

        it('renders multiple items with mixed conditions', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('status', 'active'));
            $compo->add(new icms_db_criteria_Item('age', '18', '>='), 'AND');
            $compo->add(new icms_db_criteria_Item('country', 'US'), 'OR');

            $result = $compo->render();
            expect($result)->toContain('status = \'active\'');
            expect($result)->toContain('AND');
            expect($result)->toContain('OR');
        });

        it('wraps result in parentheses', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('id', '1'));

            $result = $compo->render();
            expect($result)->toStartWith('(');
            expect($result)->toEndWith(')');
        });

        it('renders nested compositions', function () {
            $subCompo = new icms_db_criteria_Compo();
            $subCompo->add(new icms_db_criteria_Item('age', '18', '>='));
            $subCompo->add(new icms_db_criteria_Item('age', '65', '<'), 'AND');

            $mainCompo = new icms_db_criteria_Compo();
            $mainCompo->add(new icms_db_criteria_Item('status', 'active'));
            $mainCompo->add($subCompo, 'AND');

            $result = $mainCompo->render();
            expect($result)->toContain('status = \'active\'');
            expect($result)->toContain('age >= \'18\'');
            expect($result)->toContain('age < \'65\'');
        });
    });

    describe('renderWhere method', function () {
        it('returns empty string for empty composition', function () {
            $compo = new icms_db_criteria_Compo();
            expect($compo->renderWhere())->toBe('');
        });

        it('renders WHERE clause for single item', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('username', 'john'));
            expect($compo->renderWhere())->toBe("WHERE (username = 'john')");
        });

        it('renders WHERE clause for multiple items', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('status', 'active'));
            $compo->add(new icms_db_criteria_Item('deleted', '0'), 'AND');

            expect($compo->renderWhere())->toStartWith('WHERE');
            expect($compo->renderWhere())->toContain('status = \'active\'');
            expect($compo->renderWhere())->toContain('AND');
        });
    });

    describe('renderLdap method', function () {
        it('returns empty string for empty composition', function () {
            $compo = new icms_db_criteria_Compo();
            expect($compo->renderLdap())->toBe('');
        });

        it('renders single LDAP filter', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('uid', '1000'));
            expect($compo->renderLdap())->toBe('(uid=1000)');
        });

        it('renders two filters with AND', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('uid', '1000'));
            $compo->add(new icms_db_criteria_Item('status', 'active'), 'AND');

            $result = $compo->renderLdap();
            expect($result)->toBeValidLdapFilter();
            expect($result)->toContain('&');
            expect($result)->toContain('uid=1000');
            expect($result)->toContain('status=active');
        });

        it('renders two filters with OR', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('role', 'admin'));
            $compo->add(new icms_db_criteria_Item('role', 'moderator'), 'OR');

            $result = $compo->renderLdap();
            expect($result)->toBeValidLdapFilter();
            expect($result)->toContain('|');
        });

        it('renders complex LDAP filter with multiple conditions', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('objectClass', 'person'));
            $compo->add(new icms_db_criteria_Item('uid', '1000', '>'), 'AND');
            $compo->add(new icms_db_criteria_Item('status', 'active'), 'AND');

            $result = $compo->renderLdap();
            expect($result)->toBeValidLdapFilter();
        });

        it('handles nested compositions in LDAP', function () {
            $subCompo = new icms_db_criteria_Compo();
            $subCompo->add(new icms_db_criteria_Item('age', '18', '>'));
            $subCompo->add(new icms_db_criteria_Item('age', '65', '<'), 'AND');

            $mainCompo = new icms_db_criteria_Compo();
            $mainCompo->add(new icms_db_criteria_Item('status', 'active'));
            $mainCompo->add($subCompo, 'AND');

            $result = $mainCompo->renderLdap();
            expect($result)->toBeValidLdapFilter();
        });
    });

    describe('complex query scenarios', function () {
        it('builds query for user search', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('status', 'active'));
            $compo->add(new icms_db_criteria_Item('level', '0', '>'), 'AND');
            $compo->setSort('username');
            $compo->setOrder('ASC');
            $compo->setLimit(20);
            $compo->setStart(0);

            expect($compo->render())->toContain('status = \'active\'');
            expect($compo->getSort())->toBe('username');
            expect($compo->getLimit())->toBe(20);
        });

        it('builds OR query for multiple roles', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('role', 'admin'));
            $compo->add(new icms_db_criteria_Item('role', 'moderator'), 'OR');
            $compo->add(new icms_db_criteria_Item('role', 'editor'), 'OR');

            $result = $compo->render();
            expect($result)->toContain('OR');
            expect($result)->toContain('role = \'admin\'');
            expect($result)->toContain('role = \'moderator\'');
            expect($result)->toContain('role = \'editor\'');
        });

        it('builds complex nested query', function () {
            // (status = 'active' AND (age >= 18 OR verified = 1))
            $ageOrVerified = new icms_db_criteria_Compo();
            $ageOrVerified->add(new icms_db_criteria_Item('age', '18', '>='));
            $ageOrVerified->add(new icms_db_criteria_Item('verified', '1'), 'OR');

            $mainCompo = new icms_db_criteria_Compo();
            $mainCompo->add(new icms_db_criteria_Item('status', 'active'));
            $mainCompo->add($ageOrVerified, 'AND');

            $result = $mainCompo->render();
            expect($result)->toContain('status = \'active\'');
            expect($result)->toContain('AND');
        });
    });

    describe('inherited properties', function () {
        it('can use sort, limit, and start', function () {
            $compo = new icms_db_criteria_Compo();
            $compo->add(new icms_db_criteria_Item('status', 'active'));
            $compo->setSort('created_date');
            $compo->setOrder('DESC');
            $compo->setLimit(10);
            $compo->setStart(20);

            expect($compo->getSort())->toBe('created_date');
            expect($compo->getOrder())->toBe('DESC');
            expect($compo->getLimit())->toBe(10);
            expect($compo->getStart())->toBe(20);
        });
    });
});

