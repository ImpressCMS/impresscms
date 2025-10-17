<?php

/**
 * Tests for icms_db_criteria_Element
 *
 * @copyright	https://www.impresscms.org/ The ImpressCMS Project
 * @license		MIT
 * @category	ICMS
 * @package		Tests
 * @subpackage	Database
 */

use Tests\TestCase;

/**
 * Concrete implementation of icms_db_criteria_Element for testing
 */
class TestCriteriaElement extends icms_db_criteria_Element
{
    public function render(): string
    {
        return 'test_render';
    }
}

describe('icms_db_criteria_Element', function () {

    beforeEach(function () {
        $this->element = new TestCriteriaElement();
    });

    describe('constructor', function () {
        it('initializes with default values', function () {
            expect($this->element->order)->toBe('ASC');
            expect($this->element->sort)->toBe('');
            expect($this->element->limit)->toBe(0);
            expect($this->element->start)->toBe(0);
            expect($this->element->groupby)->toBe('');
        });
    });

    describe('setSort and getSort', function () {
        it('can set and get sort field', function () {
            $this->element->setSort('username');
            expect($this->element->getSort())->toBe('username');
        });

        it('can set sort to empty string', function () {
            $this->element->setSort('username');
            $this->element->setSort('');
            expect($this->element->getSort())->toBe('');
        });

        it('can set sort with table prefix', function () {
            $this->element->setSort('users.username');
            expect($this->element->getSort())->toBe('users.username');
        });
    });

    describe('setOrder and getOrder', function () {
        it('defaults to ASC order', function () {
            expect($this->element->getOrder())->toBe('ASC');
        });

        it('can set order to DESC', function () {
            $this->element->setOrder('DESC');
            expect($this->element->getOrder())->toBe('DESC');
        });

        it('can set order to desc (lowercase)', function () {
            $this->element->setOrder('desc');
            expect($this->element->getOrder())->toBe('DESC');
        });

        it('ignores invalid order values', function () {
            $this->element->setOrder('INVALID');
            expect($this->element->getOrder())->toBe('ASC');
        });

        it('can set order back to ASC', function () {
            $this->element->setOrder('DESC');
            $this->element->setOrder('ASC');
            expect($this->element->getOrder())->toBe('ASC');
        });

        it('only accepts DESC as non-ASC value', function () {
            $this->element->setOrder('RANDOM');
            expect($this->element->getOrder())->toBe('ASC');

            $this->element->setOrder('ASCENDING');
            expect($this->element->getOrder())->toBe('ASC');
        });
    });

    describe('setLimit and getLimit', function () {
        it('defaults to zero limit', function () {
            expect($this->element->getLimit())->toBe(0);
        });

        it('can set positive limit', function () {
            $this->element->setLimit(10);
            expect($this->element->getLimit())->toBe(10);
        });

        it('can set large limit', function () {
            $this->element->setLimit(1000);
            expect($this->element->getLimit())->toBe(1000);
        });

        it('converts string to integer', function () {
            $this->element->setLimit('25');
            expect($this->element->getLimit())->toBe(25);
        });

        it('handles negative values', function () {
            $this->element->setLimit(-5);
            expect($this->element->getLimit())->toBe(-5);
        });

        it('can reset limit to zero', function () {
            $this->element->setLimit(50);
            $this->element->setLimit(0);
            expect($this->element->getLimit())->toBe(0);
        });
    });

    describe('setStart and getStart', function () {
        it('defaults to zero start', function () {
            expect($this->element->getStart())->toBe(0);
        });

        it('can set positive start offset', function () {
            $this->element->setStart(20);
            expect($this->element->getStart())->toBe(20);
        });

        it('can set large start offset', function () {
            $this->element->setStart(5000);
            expect($this->element->getStart())->toBe(5000);
        });

        it('converts string to integer', function () {
            $this->element->setStart('100');
            expect($this->element->getStart())->toBe(100);
        });

        it('handles negative values by making them positive', function () {
            $this->element->setStart(-10);
            expect($this->element->getStart())->toBe(10);
        });

        it('can reset start to zero', function () {
            $this->element->setStart(100);
            $this->element->setStart(0);
            expect($this->element->getStart())->toBe(0);
        });
    });

    describe('setGroupby and getGroupby', function () {
        it('defaults to empty groupby', function () {
            expect($this->element->groupby)->toBe('');
        });

        it('can set groupby field', function () {
            $this->element->setGroupby('category');
            expect($this->element->groupby)->toBe('category');
        });

        it('returns groupby with GROUP BY prefix', function () {
            $this->element->setGroupby('category');
            expect($this->element->getGroupby())->toBe(' GROUP BY category');
        });

        it('can set multiple groupby fields', function () {
            $this->element->setGroupby('category, status');
            expect($this->element->getGroupby())->toBe(' GROUP BY category, status');
        });

        it('can set groupby with table prefix', function () {
            $this->element->setGroupby('users.role');
            expect($this->element->getGroupby())->toBe(' GROUP BY users.role');
        });
    });

    describe('method chaining', function () {
        it('can chain multiple setter calls', function () {
            $this->element
                ->setSort('username')
                ->setOrder('DESC')
                ->setLimit(10)
                ->setStart(20);

            expect($this->element->getSort())->toBe('username');
            expect($this->element->getOrder())->toBe('DESC');
            expect($this->element->getLimit())->toBe(10);
            expect($this->element->getStart())->toBe(20);
        });
    });

    describe('render method', function () {
        it('must be implemented by concrete classes', function () {
            expect($this->element->render())->toBe('test_render');
        });
    });

    describe('pagination scenarios', function () {
        it('can configure for first page', function () {
            $this->element->setLimit(20);
            $this->element->setStart(0);

            expect($this->element->getLimit())->toBe(20);
            expect($this->element->getStart())->toBe(0);
        });

        it('can configure for second page', function () {
            $this->element->setLimit(20);
            $this->element->setStart(20);

            expect($this->element->getLimit())->toBe(20);
            expect($this->element->getStart())->toBe(20);
        });

        it('can configure for arbitrary page', function () {
            $perPage = 25;
            $page = 5;
            $offset = ($page - 1) * $perPage;

            $this->element->setLimit($perPage);
            $this->element->setStart($offset);

            expect($this->element->getLimit())->toBe(25);
            expect($this->element->getStart())->toBe(100);
        });
    });

    describe('sorting scenarios', function () {
        it('can configure ascending sort', function () {
            $this->element->setSort('created_date');
            $this->element->setOrder('ASC');

            expect($this->element->getSort())->toBe('created_date');
            expect($this->element->getOrder())->toBeAscOrDesc();
        });

        it('can configure descending sort', function () {
            $this->element->setSort('created_date');
            $this->element->setOrder('DESC');

            expect($this->element->getSort())->toBe('created_date');
            expect($this->element->getOrder())->toBe('DESC');
        });
    });
});

