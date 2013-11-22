<?php

namespace WeiTest\Db;

use WeiTest\TestCase;
use Wei\Record;

/**
 * @property Record $record
 */
class QueryTest extends TestCase
{
    /**
     * The following test is from doctrine/dbal
     *
     * @link https://github.com/doctrine/dbal/blob/master/tests/Doctrine/Tests/DBAL/Query/RecordTest.php
     */
    public function testSimpleSelect()
    {
        $qb = $this->record;

        $qb->select('u.id')
            ->from('users u');

        $this->assertEquals('SELECT u.id FROM users u', (string) $qb);
    }

    public function testSelectWithSimpleWhere()
    {
        $qb   = $this->record;

        $qb->select('u.id')
            ->from('users u')
            ->where('u.nickname = ?', '1');

        $this->assertEquals("SELECT u.id FROM users u WHERE u.nickname = ?", (string) $qb);
    }

    public function testSelectWithLeftJoin()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->leftJoin('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u LEFT JOIN phones p ON p.user_id = u.id', $qb->getSql());
    }

    public function testSelectWithJoin()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->join('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u INNER JOIN phones p ON p.user_id = u.id', (string) $qb);
    }

    public function testSelectWithInnerJoin()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->innerJoin('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u INNER JOIN phones p ON p.user_id = u.id', (string) $qb);
    }

    public function testSelectWithRightJoin()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->rightJoin('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u RIGHT JOIN phones p ON p.user_id = u.id', (string) $qb);
    }

    public function testSelectWithAndWhereConditions()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->where('u.username = ?')
            ->andWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (u.username = ?) AND (u.name = ?)', (string) $qb);
    }

    public function testSelectWithOrWhereConditions()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->where('u.username = ?')
            ->orWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (u.username = ?) OR (u.name = ?)', (string) $qb);
    }

    public function testSelectWithOrOrWhereConditions()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->orWhere('u.username = ?')
            ->orWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (u.username = ?) OR (u.name = ?)', (string) $qb);
    }

    public function testSelectWithAndOrWhereConditions()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->where('u.username = ?')
            ->andWhere('u.username = ?')
            ->orWhere('u.name = ?')
            ->andWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (((u.username = ?) AND (u.username = ?)) OR (u.name = ?)) AND (u.name = ?)', (string) $qb);
    }

    public function testSelectGroupBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id', (string) $qb);
    }

    public function testSelectEmptyGroupBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->groupBy(array())
            ->from('users u');

        $this->assertEquals('SELECT u.*, p.* FROM users u', (string) $qb);
    }

    public function testSelectEmptyAddGroupBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->addGroupBy(array())
            ->from('users u');

        $this->assertEquals('SELECT u.*, p.* FROM users u', (string) $qb);
    }

    public function testSelectAddGroupBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->addGroupBy('u.foo');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id, u.foo', (string) $qb);
    }

    public function testSelectAddGroupBys()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->addGroupBy('u.foo', 'u.bar');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id, u.foo, u.bar', (string) $qb);
    }

    public function testSelectHaving()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->having('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING u.name = ?', (string) $qb);
    }

    public function testSelectAndHaving()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->andHaving('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING u.name = ?', (string) $qb);
    }

    public function testSelectHavingAndHaving()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->having('u.name = ?')
            ->andHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING (u.name = ?) AND (u.username = ?)', (string) $qb);
    }

    public function testSelectHavingOrHaving()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->having('u.name = ?')
            ->orHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING (u.name = ?) OR (u.username = ?)', (string) $qb);
    }

    public function testSelectOrHavingOrHaving()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->orHaving('u.name = ?')
            ->orHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING (u.name = ?) OR (u.username = ?)', (string) $qb);
    }

    public function testSelectHavingAndOrHaving()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->groupBy('u.id')
            ->having('u.name = ?')
            ->orHaving('u.username = ?')
            ->andHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING ((u.name = ?) OR (u.username = ?)) AND (u.username = ?)', (string) $qb);
    }

    public function testSelectOrderBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->orderBy('u.name');

        $this->assertEquals('SELECT u.*, p.* FROM users u ORDER BY u.name ASC', (string) $qb);
    }

    public function testSelectAddOrderBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->orderBy('u.name')
            ->addOrderBy('u.username', 'DESC');

        $this->assertEquals('SELECT u.*, p.* FROM users u ORDER BY u.name ASC, u.username DESC', (string) $qb);
    }

    public function testSelectAddAddOrderBy()
    {
        $qb   = $this->record;

        $qb->select('u.*', 'p.*')
            ->from('users u')
            ->addOrderBy('u.name')
            ->addOrderBy('u.username', 'DESC');

        $this->assertEquals('SELECT u.*, p.* FROM users u ORDER BY u.name ASC, u.username DESC', (string) $qb);
    }

    public function testEmptySelect()
    {
        $qb   = $this->record;
        $qb2 = $qb->select();

        $this->assertSame($qb, $qb2);
        $this->assertEquals(Record::SELECT, $qb->getType());
    }

    public function testSelectAddSelect()
    {
        $qb   = $this->record;

        $qb->select('u.*')
            ->addSelect('p.*')
            ->from('users u');

        $this->assertEquals('SELECT u.*, p.* FROM users u', (string) $qb);
    }

    public function testEmptyAddSelect()
    {
        $qb   = $this->record;
        $qb2 = $qb->addSelect();

        $this->assertSame($qb, $qb2);
        $this->assertEquals(Record::SELECT, $qb->getType());
    }

    public function testGetState()
    {
        $qb   = $this->record;

        $this->assertEquals(Record::STATE_CLEAN, $qb->getState());

        $qb->select('u.*')->from('users u');

        $this->assertEquals(Record::STATE_DIRTY, $qb->getState());

        $sql1 = $qb->getSql();

        $this->assertEquals(Record::STATE_CLEAN, $qb->getState());
        $this->assertEquals($sql1, $qb->getSql());
    }

    public function testLimit()
    {
        $qb   = $this->record;
        $qb->limit(10);

        $this->assertEquals(Record::STATE_DIRTY, $qb->getState());
        $this->assertEQuals(10, $qb->getSqlPart('limit'));
    }

    public function testSetFirstResult()
    {
        $qb   = $this->record;
        $qb->limit(10);

        $this->assertEquals(Record::STATE_DIRTY, $qb->getState());
        $this->assertEQuals(10, $qb->getSqlPart('limit'));
    }

    public function testResetQueryPart()
    {
        $qb   = $this->record;

        $qb->select('u.*')->from('users u')->where('u.name = ?');

        $this->assertEquals('SELECT u.* FROM users u WHERE u.name = ?', (string)$qb);
        $qb->reset('where');
        $this->assertEquals('SELECT u.* FROM users u', (string)$qb);
    }

    public function testResetQueryParts()
    {
        $qb   = $this->record;

        $qb->select('u.*')->from('users u')->where('u.name = ?')->orderBy('u.name');

        $this->assertEquals('SELECT u.* FROM users u WHERE u.name = ? ORDER BY u.name ASC', (string)$qb);
        $qb->resetAll(array('where', 'orderBy'));
        $this->assertEquals('SELECT u.* FROM users u', (string)$qb);
    }

    public function testFrom()
    {
        $qb = $this->record;

        // users table would be overwrite
        $qb->from('users')->from('groups');

        $this->assertEquals("SELECT * FROM groups", $qb->getSql());
    }
}
