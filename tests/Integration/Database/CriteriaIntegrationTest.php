<?php

/**
 * Integration Tests for Database Criteria System
 *
 * These tests verify that the criteria classes work together correctly
 * to build complex database queries.
 *
 * @copyright	https://www.impresscms.org/ The ImpressCMS Project
 * @license		MIT
 * @category	ICMS
 * @package		Tests
 * @subpackage	Integration
 */

use Tests\TestCase;

describe('Criteria System Integration', function () {

    describe('simple query building', function () {
        it('builds basic WHERE clause', function () {
            $criteria = new icms_db_criteria_Item('status', 'active');
            $where = $criteria->renderWhere();

            expect($where)->toBe("WHERE status = 'active'");
        });

        it('builds WHERE clause with multiple conditions', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active'));
            $criteria->add(new icms_db_criteria_Item('deleted', '0'));

            $where = $criteria->renderWhere();

            expect($where)->toStartWith('WHERE');
            expect($where)->toContain('status = \'active\'');
            expect($where)->toContain('deleted = \'0\'');
            expect($where)->toContain('AND');
        });
    });

    describe('user search scenarios', function () {
        it('builds query for active users', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('level', '0', '>'));
            $criteria->add(new icms_db_criteria_Item('user_regdate', time() - 86400, '>'));
            $criteria->setSort('uname');
            $criteria->setOrder('ASC');
            $criteria->setLimit(20);

            expect($criteria->render())->toContain('level > ');
            expect($criteria->render())->toContain('user_regdate > ');
            expect($criteria->getSort())->toBe('uname');
            expect($criteria->getLimit())->toBe(20);
        });

        it('builds query for users by role', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('role', 'admin'));
            $criteria->add(new icms_db_criteria_Item('role', 'moderator'), 'OR');
            $criteria->add(new icms_db_criteria_Item('role', 'editor'), 'OR');

            $result = $criteria->render();

            expect($result)->toContain('role = \'admin\'');
            expect($result)->toContain('OR');
            expect($result)->toContain('role = \'moderator\'');
            expect($result)->toContain('role = \'editor\'');
        });

        it('builds query with username search', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('uname', '%john%', 'LIKE'));
            $criteria->add(new icms_db_criteria_Item('level', '0', '>'));
            $criteria->setSort('uname');

            expect($criteria->render())->toContain('LIKE');
            expect($criteria->render())->toContain('%john%');
        });
    });

    describe('content filtering scenarios', function () {
        it('builds query for published articles', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('published', '1'));
            $criteria->add(new icms_db_criteria_Item('publish_date', time(), '<='));
            $criteria->add(new icms_db_criteria_Item('expire_date', time(), '>='));
            $criteria->setSort('publish_date');
            $criteria->setOrder('DESC');
            $criteria->setLimit(10);

            expect($criteria->render())->toContain('published = \'1\'');
            expect($criteria->getOrder())->toBe('DESC');
            expect($criteria->getLimit())->toBe(10);
        });

        it('builds query for articles by category', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('categoryid', '(1,2,3)', 'IN'));
            $criteria->add(new icms_db_criteria_Item('status', 'published'));

            expect($criteria->render())->toContain('IN (1,2,3)');
            expect($criteria->render())->toContain('status = \'published\'');
        });
    });

    describe('complex nested queries', function () {
        it('builds query with nested OR conditions', function () {
            // (status = 'active' AND (role = 'admin' OR role = 'moderator'))
            $rolesCriteria = new icms_db_criteria_Compo();
            $rolesCriteria->add(new icms_db_criteria_Item('role', 'admin'));
            $rolesCriteria->add(new icms_db_criteria_Item('role', 'moderator'), 'OR');

            $mainCriteria = new icms_db_criteria_Compo();
            $mainCriteria->add(new icms_db_criteria_Item('status', 'active'));
            $mainCriteria->add($rolesCriteria, 'AND');

            $result = $mainCriteria->render();

            expect($result)->toContain('status = \'active\'');
            expect($result)->toContain('role = \'admin\'');
            expect($result)->toContain('OR');
            expect($result)->toContain('role = \'moderator\'');
        });

        it('builds query with age range and verification', function () {
            // (status = 'active' AND (age >= 18 AND age < 65) AND verified = 1)
            $ageCriteria = new icms_db_criteria_Compo();
            $ageCriteria->add(new icms_db_criteria_Item('age', '18', '>='));
            $ageCriteria->add(new icms_db_criteria_Item('age', '65', '<'), 'AND');

            $mainCriteria = new icms_db_criteria_Compo();
            $mainCriteria->add(new icms_db_criteria_Item('status', 'active'));
            $mainCriteria->add($ageCriteria, 'AND');
            $mainCriteria->add(new icms_db_criteria_Item('verified', '1'), 'AND');

            $result = $mainCriteria->render();

            expect($result)->toContain('status = \'active\'');
            expect($result)->toContain('age >= \'18\'');
            expect($result)->toContain('age < \'65\'');
            expect($result)->toContain('verified = \'1\'');
        });

        it('builds query with multiple nested levels', function () {
            // ((cat1 OR cat2) AND (status1 OR status2))
            $categoryCriteria = new icms_db_criteria_Compo();
            $categoryCriteria->add(new icms_db_criteria_Item('category', '1'));
            $categoryCriteria->add(new icms_db_criteria_Item('category', '2'), 'OR');

            $statusCriteria = new icms_db_criteria_Compo();
            $statusCriteria->add(new icms_db_criteria_Item('status', 'published'));
            $statusCriteria->add(new icms_db_criteria_Item('status', 'featured'), 'OR');

            $mainCriteria = new icms_db_criteria_Compo();
            $mainCriteria->add($categoryCriteria);
            $mainCriteria->add($statusCriteria, 'AND');

            $result = $mainCriteria->render();

            expect($result)->toContain('category = \'1\'');
            expect($result)->toContain('category = \'2\'');
            expect($result)->toContain('status = \'published\'');
            expect($result)->toContain('status = \'featured\'');
        });
    });

    describe('pagination scenarios', function () {
        it('builds query for first page', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active'));
            $criteria->setSort('created_date');
            $criteria->setOrder('DESC');
            $criteria->setLimit(20);
            $criteria->setStart(0);

            expect($criteria->getLimit())->toBe(20);
            expect($criteria->getStart())->toBe(0);
        });

        it('builds query for page 5', function () {
            $perPage = 20;
            $page = 5;
            $offset = ($page - 1) * $perPage;

            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active'));
            $criteria->setLimit($perPage);
            $criteria->setStart($offset);

            expect($criteria->getLimit())->toBe(20);
            expect($criteria->getStart())->toBe(80);
        });
    });

    describe('sorting scenarios', function () {
        it('builds query with single sort field', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active'));
            $criteria->setSort('username');
            $criteria->setOrder('ASC');

            expect($criteria->getSort())->toBe('username');
            expect($criteria->getOrder())->toBe('ASC');
        });

        it('builds query with table-prefixed sort', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active', '=', 'users'));
            $criteria->setSort('users.created_date');
            $criteria->setOrder('DESC');

            expect($criteria->getSort())->toBe('users.created_date');
        });
    });

    describe('grouping scenarios', function () {
        it('builds query with GROUP BY', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active'));
            $criteria->setGroupby('category_id');

            expect($criteria->getGroupby())->toContain('GROUP BY');
            expect($criteria->getGroupby())->toContain('category_id');
        });

        it('builds query with multiple GROUP BY fields', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('status', 'active'));
            $criteria->setGroupby('category_id, author_id');

            expect($criteria->getGroupby())->toContain('category_id, author_id');
        });
    });

    describe('NULL handling scenarios', function () {
        it('builds query with IS NULL', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('deleted_at', '', 'IS NULL'));
            $criteria->add(new icms_db_criteria_Item('status', 'active'));

            expect($criteria->render())->toContain('IS NULL');
            expect($criteria->render())->toContain('status = \'active\'');
        });

        it('builds query with IS NOT NULL', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('email', '', 'IS NOT NULL'));
            $criteria->add(new icms_db_criteria_Item('verified', '1'));

            expect($criteria->render())->toContain('IS NOT NULL');
        });
    });

    describe('LDAP filter generation', function () {
        it('generates LDAP filter for simple query', function () {
            $criteria = new icms_db_criteria_Item('uid', '1000');
            expect($criteria->renderLdap())->toBe('(uid=1000)');
        });

        it('generates LDAP filter for AND composition', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('objectClass', 'person'));
            $criteria->add(new icms_db_criteria_Item('uid', '1000', '>'), 'AND');

            $result = $criteria->renderLdap();
            expect($result)->toBeValidLdapFilter();
            expect($result)->toContain('&');
        });

        it('generates LDAP filter for OR composition', function () {
            $criteria = new icms_db_criteria_Compo();
            $criteria->add(new icms_db_criteria_Item('role', 'admin'));
            $criteria->add(new icms_db_criteria_Item('role', 'moderator'), 'OR');

            $result = $criteria->renderLdap();
            expect($result)->toBeValidLdapFilter();
            expect($result)->toContain('|');
        });
    });
});

