<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Twin
 * Date: 13-6-3
 * Time: 下午1:13
 * To change this template use File | Settings | File Templates.
 */

namespace WidgetTest\DbTest;

use WidgetTest\TestCase;
use Widget\Db\QueryBuilder;

class QueryTest extends TestCase
{
    /**
     * The following test is from doctrine/dbal
     *
     * @link https://github.com/doctrine/dbal/blob/master/tests/Doctrine/Tests/DBAL/Query/QueryBuilderTest.php
     */
    public function testSimpleSelect()
    {
        $qb = new QueryBuilder($this->db);

        $qb->select('u.id')
            ->from('users', 'u');

        $this->assertEquals('SELECT u.id FROM users u', (string) $qb);
    }

    public function testSelectWithSimpleWhere()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.id')
            ->from('users', 'u')
            ->where('u.nickname = ?', '1');

        $this->assertEquals("SELECT u.id FROM users u WHERE u.nickname = ?", (string) $qb);
    }

    public function testSelectWithLeftJoin()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->leftJoin('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u LEFT JOIN phones p ON p.user_id = u.id', $qb->getSql());
    }

    public function testSelectWithJoin()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->Join('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u INNER JOIN phones p ON p.user_id = u.id', (string) $qb);
    }

    public function testSelectWithInnerJoin()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->innerJoin('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u INNER JOIN phones p ON p.user_id = u.id', (string) $qb);
    }

    public function testSelectWithRightJoin()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->rightJoin('phones p', 'p.user_id = u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u RIGHT JOIN phones p ON p.user_id = u.id', (string) $qb);
    }

    public function testSelectWithAndWhereConditions()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->where('u.username = ?')
            ->andWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (u.username = ?) AND (u.name = ?)', (string) $qb);
    }

    public function testSelectWithOrWhereConditions()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->where('u.username = ?')
            ->orWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (u.username = ?) OR (u.name = ?)', (string) $qb);
    }

    public function testSelectWithOrOrWhereConditions()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->orWhere('u.username = ?')
            ->orWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (u.username = ?) OR (u.name = ?)', (string) $qb);
    }

    public function testSelectWithAndOrWhereConditions()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->where('u.username = ?')
            ->andWhere('u.username = ?')
            ->orWhere('u.name = ?')
            ->andWhere('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u WHERE (((u.username = ?) AND (u.username = ?)) OR (u.name = ?)) AND (u.name = ?)', (string) $qb);
    }

    public function testSelectGroupBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id', (string) $qb);
    }

    public function testSelectEmptyGroupBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->groupBy(array())
            ->from('users', 'u');

        $this->assertEquals('SELECT u.*, p.* FROM users u', (string) $qb);
    }

    public function testSelectEmptyAddGroupBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->addGroupBy(array())
            ->from('users', 'u');

        $this->assertEquals('SELECT u.*, p.* FROM users u', (string) $qb);
    }

    public function testSelectAddGroupBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->addGroupBy('u.foo');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id, u.foo', (string) $qb);
    }

    public function testSelectAddGroupBys()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->addGroupBy('u.foo', 'u.bar');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id, u.foo, u.bar', (string) $qb);
    }

    public function testSelectHaving()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->having('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING u.name = ?', (string) $qb);
    }

    public function testSelectAndHaving()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->andHaving('u.name = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING u.name = ?', (string) $qb);
    }

    public function testSelectHavingAndHaving()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->having('u.name = ?')
            ->andHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING (u.name = ?) AND (u.username = ?)', (string) $qb);
    }

    public function testSelectHavingOrHaving()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->having('u.name = ?')
            ->orHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING (u.name = ?) OR (u.username = ?)', (string) $qb);
    }

    public function testSelectOrHavingOrHaving()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->orHaving('u.name = ?')
            ->orHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING (u.name = ?) OR (u.username = ?)', (string) $qb);
    }

    public function testSelectHavingAndOrHaving()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->groupBy('u.id')
            ->having('u.name = ?')
            ->orHaving('u.username = ?')
            ->andHaving('u.username = ?');

        $this->assertEquals('SELECT u.*, p.* FROM users u GROUP BY u.id HAVING ((u.name = ?) OR (u.username = ?)) AND (u.username = ?)', (string) $qb);
    }

    public function testSelectOrderBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->orderBy('u.name');

        $this->assertEquals('SELECT u.*, p.* FROM users u ORDER BY u.name ASC', (string) $qb);
    }

    public function testSelectAddOrderBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->orderBy('u.name')
            ->addOrderBy('u.username', 'DESC');

        $this->assertEquals('SELECT u.*, p.* FROM users u ORDER BY u.name ASC, u.username DESC', (string) $qb);
    }

    public function testSelectAddAddOrderBy()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*', 'p.*')
            ->from('users', 'u')
            ->addOrderBy('u.name')
            ->addOrderBy('u.username', 'DESC');

        $this->assertEquals('SELECT u.*, p.* FROM users u ORDER BY u.name ASC, u.username DESC', (string) $qb);
    }

    public function testEmptySelect()
    {
        $qb   = new QueryBuilder($this->db);
        $qb2 = $qb->select();

        $this->assertSame($qb, $qb2);
        $this->assertEquals(QueryBuilder::SELECT, $qb->getType());
    }

    public function testSelectAddSelect()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*')
            ->addSelect('p.*')
            ->from('users', 'u');

        $this->assertEquals('SELECT u.*, p.* FROM users u', (string) $qb);
    }

    public function testEmptyAddSelect()
    {
        $qb   = new QueryBuilder($this->db);
        $qb2 = $qb->addSelect();

        $this->assertSame($qb, $qb2);
        $this->assertEquals(QueryBuilder::SELECT, $qb->getType());
    }

    public function testUpdate()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->update('users', 'u')
            ->set('u.foo', '?')
            ->set('u.bar', '?');

        $this->assertEquals(QueryBuilder::UPDATE, $qb->getType());
        $this->assertEquals('UPDATE users u SET u.foo = ?, u.bar = ?', (string) $qb);
    }

    public function testUpdateWithoutAlias()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->update('users')
            ->set('foo', '?')
            ->set('bar', '?');

        $this->assertEquals('UPDATE users SET foo = ?, bar = ?', (string) $qb);
    }

    public function testUpdateWhere()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->update('users', 'u')
            ->set('u.foo', '?')
            ->where('u.foo = ?');

        $this->assertEquals('UPDATE users u SET u.foo = ? WHERE u.foo = ?', (string) $qb);
    }

    public function testEmptyUpdate()
    {
        $qb   = new QueryBuilder($this->db);
        $qb2 = $qb->update();

        $this->assertEquals(QueryBuilder::UPDATE, $qb->getType());
        $this->assertSame($qb2, $qb);
    }

    public function testDelete()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->delete('users', 'u');

        $this->assertEquals(QueryBuilder::DELETE, $qb->getType());
        $this->assertEquals('DELETE FROM users u', (string) $qb);
    }

    public function testDeleteWithoutAlias()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->delete('users');

        $this->assertEquals(QueryBuilder::DELETE, $qb->getType());
        $this->assertEquals('DELETE FROM users', (string) $qb);
    }

    public function testDeleteWhere()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->delete('users', 'u')
            ->where('u.foo = ?');

        $this->assertEquals('DELETE FROM users u WHERE u.foo = ?', (string) $qb);
    }

    public function testEmptyDelete()
    {
        $qb   = new QueryBuilder($this->db);
        $qb2 = $qb->delete();

        $this->assertEquals(QueryBuilder::DELETE, $qb->getType());
        $this->assertSame($qb2, $qb);
    }

    public function testGetDb()
    {
        $qb   = new QueryBuilder($this->db);
        $this->assertSame($this->db, $qb->getDb());
    }

    public function testGetState()
    {
        $qb   = new QueryBuilder($this->db);

        $this->assertEquals(QueryBuilder::STATE_CLEAN, $qb->getState());

        $qb->select('u.*')->from('users', 'u');

        $this->assertEquals(QueryBuilder::STATE_DIRTY, $qb->getState());

        $sql1 = $qb->getSql();

        $this->assertEquals(QueryBuilder::STATE_CLEAN, $qb->getState());
        $this->assertEquals($sql1, $qb->getSql());
    }

    public function testLimit()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->limit(10);

        $this->assertEquals(QueryBuilder::STATE_DIRTY, $qb->getState());
        $this->assertEQuals(10, $qb->get('limit'));
    }

    public function testSetFirstResult()
    {
        $qb   = new QueryBuilder($this->db);
        $qb->limit(10);

        $this->assertEquals(QueryBuilder::STATE_DIRTY, $qb->getState());
        $this->assertEQuals(10, $qb->get('limit'));
    }

    public function testResetQueryPart()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*')->from('users', 'u')->where('u.name = ?');

        $this->assertEquals('SELECT u.* FROM users u WHERE u.name = ?', (string)$qb);
        $qb->resetQueryPart('where');
        $this->assertEquals('SELECT u.* FROM users u', (string)$qb);
    }

    public function testResetQueryParts()
    {
        $qb   = new QueryBuilder($this->db);

        $qb->select('u.*')->from('users', 'u')->where('u.name = ?')->orderBy('u.name');

        $this->assertEquals('SELECT u.* FROM users u WHERE u.name = ? ORDER BY u.name ASC', (string)$qb);
        $qb->resetQueryParts(array('where', 'orderBy'));
        $this->assertEquals('SELECT u.* FROM users u', (string)$qb);
    }

    public function testFrom()
    {
        $qb = new QueryBuilder($this->db);

        // users table would be overwrite
        $qb->from('users')->from('groups');

        $this->assertEquals("SELECT * FROM groups", $qb->getSql());
    }
}