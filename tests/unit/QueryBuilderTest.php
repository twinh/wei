<?php

declare(strict_types=1);

namespace WeiTest;

use PDO;
use Wei\QueryBuilder as Qb;
use WeiTest\Fixtures\DbTrait;

/**
 * @mixin \DbMixin
 * @link http://edgeguides.rubyonrails.org/active_record_querying.html#conditions
 *
 * @internal
 */
final class QueryBuilderTest extends TestCase
{
    use DbTrait;

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        static::resetTablePrefix();
        parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->db->setTablePrefix('p_');
        $this->wei->setConfig('queryBuilder', [
            'dbKeyConverter' => null,
            'phpKeyConverter' => null,
        ]);
    }

    public function testSelect()
    {
        $sql = Qb::table('test_users')->select('name')->getSql();

        $this->assertEquals('SELECT `name` FROM `p_test_users`', $sql);
    }

    public function testStaticSelect()
    {
        $sql = Qb::select('name')->from('test_users')->getSql();

        $this->assertEquals('SELECT `name` FROM `p_test_users`', $sql);
    }

    public function testSelectMultipleByArray()
    {
        $sql = Qb::table('test_users')->select(['name', 'email'])->getSql();

        $this->assertEquals('SELECT `name`, `email` FROM `p_test_users`', $sql);
    }

    public function testSelectMultipleByArguments()
    {
        $sql = Qb::table('test_users')->select('name', 'email')->getSql();

        $this->assertEqualsIgnoringCase('SELECT `name`, `email` FROM `p_test_users`', $sql);
    }

    public function testSelectAlias()
    {
        $sql = Qb::table('test_users')->select(['name' => 'user_name'])->getSql();

        $this->assertEquals('SELECT `name` AS `user_name` FROM `p_test_users`', $sql);
    }

    public function testDistinct()
    {
        $qb = Qb::table('test_users')->select('name')->distinct();

        $this->assertEquals('SELECT DISTINCT `name` FROM `p_test_users`', $qb->getSql());

        $this->assertEquals('SELECT `name` FROM `p_test_users`', $qb->distinct(false)->getSql());
    }

    public function testSelectDistinct()
    {
        $sql = Qb::table('test_users')->selectDistinct('name')->getSql();

        $this->assertEquals('SELECT DISTINCT `name` FROM `p_test_users`', $sql);
    }

    public function testAddSelect()
    {
        $sql = Qb::table('test_users')->select('name')->select('email')->getSql();

        $this->assertEquals('SELECT `name`, `email` FROM `p_test_users`', $sql);
    }

    public function testSelectRaw()
    {
        $sql = Qb::table('test_users')->selectRaw('UPPER(name)')->getSql();

        $this->assertEqualsIgnoringCase('SELECT UPPER(name) FROM `p_test_users`', $sql);
    }

    public function testSelectExcept()
    {
        $this->initFixtures();

        $sql = Qb::table('test_users')->selectExcept('id')->getSql();

        $this->assertEqualsIgnoringCase(implode(' ', [
            'SELECT `group_id`, `name`, `address`, `birthday`, `joined_date`, `signature`',
            'FROM `p_test_users`',
        ]), $sql);
    }

    public function testWhere()
    {
        $sql = Qb::table('test_users')->where('name', '=', 'twin')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin'", $sql);
    }

    public function testWhereEqualShorthand()
    {
        $sql = Qb::table('test_users')->where('name', 'twin')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin'", $sql);
    }

    public function testWhereArray()
    {
        $sql = Qb::table('test_users')->where([
            ['name', 'twin'],
            ['email', '!=', 'twin@example.com'],
        ])->getRawSql();

        $this->assertEquals(implode(' ', [
            'SELECT * FROM `p_test_users`',
            "WHERE `name` = 'twin' AND `email` != 'twin@example.com'",
        ]), $sql);
    }

    public function testWhereClosure()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->where(function (Qb $qb) {
                $qb->where('email', '=', 'twin@example.com')
                    ->orWhere('score', '>', 100);
            })
            ->getRawSql();
        $this->assertEquals(implode(' ', [
            'SELECT * FROM `p_test_users`',
            "WHERE `name` = 'twin' AND (`email` = 'twin@example.com' OR `score` > 100)",
        ]), $sql);
    }

    public function testWhereIgnoreNullColumn()
    {
        $this->initFixtures();

        $sql = Qb::table('test_users')->where(null)->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users`', $sql);
    }

    public function testWhereParamIsArray()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->where('id', [1, 2]);

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `id` IN (1, 2)', $qb->getRawSql());

        $user = $qb->fetch();
        $this->assertSame('1', $user['id']);

        // NOTE: fetch 里面设置了 limit(1)
        $users = $qb->limit(2)->fetchAll();
        $this->assertSame('2', $users[1]['id']);
    }

    public function testWhereRaw()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->whereRaw("name = 'twin'");

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE name = 'twin'", $qb->getRawSql());
        $this->assertEquals('twin', $qb->fetch()['name']);
    }

    public function testWhereRawWithQuestionParam()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->whereRaw('name = ?', 'twin');

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE name = 'twin'", $qb->getRawSql());
        $this->assertEquals('twin', $qb->fetch()['name']);
    }

    public function testWhereRawWithColonParam()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->whereRaw('group_id = :groupId AND name = :name', [
            'groupId' => 1,
            'name' => 'twin',
        ]);

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE group_id = 1 AND name = 'twin'", $qb->getRawSql());
        $this->assertEquals('twin', $qb->fetch()['name']);
    }

    public function testWhereNot()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->whereNot('id', 1);
        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `id` != 1', $qb->getRawSql());
        $this->assertEquals('test', $qb->fetch()['name']);
    }

    public function testOrWhere()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhere('email', '!=', 'twin@example.com')
            ->getRawSql();

        $this->assertEquals(
            "SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `email` != 'twin@example.com'",
            $sql
        );
    }

    public function testMultipleOrWhere()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhere('email', 'twin@example.com')
            ->orWhere('first_name', '=', 'twin')
            ->getRawSql();

        $this->assertEquals(
            implode(' ', [
                'SELECT * FROM `p_test_users`',
                "WHERE `name` = 'twin' OR `email` = 'twin@example.com' OR `first_name` = 'twin'",
            ]),
            $sql
        );
    }

    public function testOrWhereArray()
    {
        $sql = Qb::table('test_users')->orWhere([
            ['name', 'twin'],
            ['email', 'twin@example.com'],
        ])->getRawSql();
        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `email` = 'twin@example.com'", $sql);
    }

    public function testOrWhereClosure()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhere(function (Qb $qb) {
                $qb->where('email', '=', 'twin@example.com')
                    ->orWhere('score', '>', 100);
            })
            ->getRawSql();

        $this->assertEquals(
            "SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR (`email` = 'twin@example.com' OR `score` > 100)",
            $sql
        );
    }

    public function testOrWhereParamIsArray()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->where('name', 'twin')->orWhere('id', [1, 2]);

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `id` IN (1, 2)", $qb->getRawSql());

        $user = $qb->fetch();
        $this->assertSame('1', $user['id']);

        // NOTE: fetch 里面设置了 limit(1)
        $users = $qb->limit(2)->fetchAll();
        $this->assertSame('2', $users[1]['id']);
    }

    public function testOrWhereRaw()
    {
        $qb = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereRaw('email = ?', 'twin@example.com');

        $this->assertEquals(
            "SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR email = 'twin@example.com'",
            $qb->getRawSql()
        );
    }

    public function testWhereBetween()
    {
        $sql = Qb::table('test_users')->whereBetween('age', [1, 10])->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `age` BETWEEN 1 AND 10', $sql);
    }

    public function testOrWhereBetween()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereBetween('age', [1, 10])->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `age` BETWEEN 1 AND 10", $sql);
    }

    public function testWhereNotBetween()
    {
        $sql = Qb::table('test_users')->whereNotBetween('age', [1, 10])->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `age` NOT BETWEEN 1 AND 10', $sql);
    }

    public function testOrWhereNotBetween()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereNotBetween('age', [1, 10])->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `age` NOT BETWEEN 1 AND 10", $sql);
    }

    public function testWhereIn()
    {
        $sql = Qb::table('test_users')->whereIn('age', [1, 10])->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `age` IN (1, 10)', $sql);
    }

    public function testOrWhereIn()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereIn('age', [1, 10])->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `age` IN (1, 10)", $sql);
    }

    public function testWhereNotIn()
    {
        $sql = Qb::table('test_users')->whereNotIn('age', [1, 10])->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `age` NOT IN (1, 10)', $sql);
    }

    public function testOrWhereNotIn()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereNotIn('age', [1, 10])->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `age` NOT IN (1, 10)", $sql);
    }

    public function testWhereNull()
    {
        $sql = Qb::table('test_users')->whereNull('age')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `age` IS NULL', $sql);
    }

    public function testOrWhereNull()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereNull('age')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `age` IS NULL", $sql);
    }

    public function testWhereNotNull()
    {
        $sql = Qb::table('test_users')->whereNotNull('age')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `age` IS NOT NULL', $sql);
    }

    public function testOrWhereNotNull()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereNotNull('age')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `age` IS NOT NULL", $sql);
    }

    public function testWhereDate()
    {
        $sql = Qb::table('test_users')->whereDate('created_at', '2020-02-02')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE DATE(`created_at`) = '2020-02-02'", $sql);
    }

    public function testOrWhereDate()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereDate('created_at', '2020-02-02')
            ->getRawSql();

        $this->assertEquals(
            "SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR DATE(`created_at`) = '2020-02-02'",
            $sql
        );
    }

    public function testWhereMonth()
    {
        $sql = Qb::table('test_users')->whereMonth('created_at', '2')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE MONTH(`created_at`) = '2'", $sql);
    }

    public function testOrWhereMonth()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereMonth('created_at', '2')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR MONTH(`created_at`) = '2'", $sql);
    }

    public function testWhereDay()
    {
        $sql = Qb::table('test_users')->whereDay('created_at', '2')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE DAY(`created_at`) = '2'", $sql);
    }

    public function testOrWhereDay()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereDay('created_at', '2')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR DAY(`created_at`) = '2'", $sql);
    }

    public function testWhereYear()
    {
        $sql = Qb::table('test_users')->whereYear('created_at', '2020')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE YEAR(`created_at`) = '2020'", $sql);
    }

    public function testOrWhereYear()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereYear('created_at', '2020')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR YEAR(`created_at`) = '2020'", $sql);
    }

    public function testWhereTime()
    {
        $sql = Qb::table('test_users')->whereTime('created_at', '20:20:20')->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE TIME(`created_at`) = '20:20:20'", $sql);
    }

    public function testOrWhereTime()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereTime('created_at', '20:20:20')
            ->getRawSql();

        $this->assertEquals(
            "SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR TIME(`created_at`) = '20:20:20'",
            $sql
        );
    }

    public function testWhereColumn()
    {
        $sql = Qb::table('test_users')
            ->whereColumn('created_at', 'updated_at')
            ->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `created_at` = `updated_at`', $sql);
    }

    public function testOrWhereColumn()
    {
        $sql = Qb::table('test_users')
            ->where('name', 'twin')
            ->orWhereColumn('created_at', 'updated_at')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin' OR `created_at` = `updated_at`", $sql);
    }

    public function testWhereContains()
    {
        $sql = Qb::table('test_users')
            ->whereContains('name', 'twin')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` LIKE '%twin%'", $sql);
    }

    public function testOrWhereContains()
    {
        $sql = Qb::table('test_users')
            ->whereContains('name', 'twin')
            ->orWhereContains('email', 'twin')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` LIKE '%twin%' OR `email` LIKE '%twin%'", $sql);
    }

    public function testWhereNotContains()
    {
        $sql = Qb::table('test_users')
            ->whereNotContains('name', 'twin')
            ->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` NOT LIKE '%twin%'", $sql);
    }

    public function testOrWhereNotContains()
    {
        $sql = Qb::table('test_users')
            ->whereNotContains('name', 'twin')
            ->orWhereNotContains('email', 'twin')
            ->getRawSql();

        $this->assertEquals(
            "SELECT * FROM `p_test_users` WHERE `name` NOT LIKE '%twin%' OR `email` NOT LIKE '%twin%'",
            $sql
        );
    }

    public function testOrderBy()
    {
        $sql = Qb::table('test_users')->orderBy('id')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` ORDER BY `id` ASC', $sql);
    }

    public function testOrderByRaw()
    {
        $sql = Qb::table('test_users')->orderByRaw('RAND()')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` ORDER BY RAND()', $sql);
    }

    public function testOrderByDesc()
    {
        $sql = Qb::table('test_users')->orderBy('id', 'DESC')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` ORDER BY `id` DESC', $sql);
    }

    public function testOrderByMultiple()
    {
        $sql = Qb::table('test_users')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` ORDER BY `created_at` DESC, `id` ASC', $sql);
    }

    public function testAsc()
    {
        $sql = Qb::table('test_users')->asc('id')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` ORDER BY `id` ASC', $sql);
    }

    public function testDesc()
    {
        $sql = Qb::table('test_users')->desc('id')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` ORDER BY `id` DESC', $sql);
    }

    public function testInvalidOrder()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Parameter for "order" must be "ASC" or "DESC".'));

        Qb::table('test_users')->orderBy('id', 'as');
    }

    public function testGroupBy()
    {
        $sql = Qb::table('test_users')->groupBy('group_id')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` GROUP BY `group_id`', $sql);
    }

    public function testGroupByMultiply()
    {
        $sql = Qb::table('test_users')->groupBy('group_id', 'type')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` GROUP BY `group_id`, `type`', $sql);
    }

    public function testGroupByTwice()
    {
        $sql = Qb::table('test_users')->groupBy('group_id')->groupBy('type')->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` GROUP BY `group_id`, `type`', $sql);
    }

    public function testHaving()
    {
        $sql = Qb::table('test_users')
            ->groupBy('group_id')
            ->having('id', '>', 1)
            ->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` GROUP BY `group_id` HAVING `id` > 1', $sql);
    }

    public function testHavingMultiply()
    {
        $sql = Qb::table('test_users')
            ->groupBy('group_id')
            ->having('id', '>', 1)
            ->having('type', 1)
            ->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` GROUP BY `group_id` HAVING `id` > 1 AND `type` = 1', $sql);
    }

    public function testHavingRaw()
    {
        $qb = Qb::table('test_users')->havingRaw('name = ?', 'twin');

        $this->assertEquals("SELECT * FROM `p_test_users` HAVING name = 'twin'", $qb->getRawSql());
    }

    public function testOrHaving()
    {
        $sql = Qb::table('test_users')
            ->having('name', 'twin')
            ->orHaving('email', '!=', 'twin@example.com')
            ->getRawSql();

        $this->assertEquals(
            "SELECT * FROM `p_test_users` HAVING `name` = 'twin' OR `email` != 'twin@example.com'",
            $sql
        );
    }

    public function testLimit()
    {
        $sql = Qb::table('test_users')->limit(1)->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` LIMIT 1', $sql);
    }

    public function testOffset()
    {
        $sql = Qb::table('test_users')->offset(1)->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` OFFSET 1', $sql);
    }

    public function testLimitOffset()
    {
        $sql = Qb::table('test_users')->limit(2)->offset(1)->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` LIMIT 2 OFFSET 1', $sql);
    }

    public function testPageLimit()
    {
        $sql = Qb::table('test_users')->page(3)->limit(3)->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` LIMIT 3 OFFSET 6', $sql);
    }

    public function testLimitPage()
    {
        $sql = Qb::table('test_users')->limit(3)->page(3)->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` LIMIT 3 OFFSET 6', $sql);
    }

    public function testWhen()
    {
        $sql = Qb::table('test_users')->when('twin', function (Qb $qb, $value) {
            $qb->where('name', $value);
        })->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin'", $sql);
    }

    public function testWhenFalse()
    {
        $sql = Qb::table('test_users')->when(false, function (Qb $qb, $value) {
            $qb->where('name', $value);
        })->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users`', $sql);
    }

    public function testWhenDefault()
    {
        $sql = Qb::table('test_users')->when(false, function (Qb $qb, $value) {
            $qb->where('name', $value);
        }, function (Qb $qb, $value) {
            $qb->where('type', 0);
        })->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `type` = 0', $sql);
    }

    public function testUnless()
    {
        $sql = Qb::table('test_users')->unless(false, function (Qb $qb, $value) {
            $qb->where('name', 'twin');
        })->getRawSql();

        $this->assertEquals("SELECT * FROM `p_test_users` WHERE `name` = 'twin'", $sql);
    }

    public function testUnlessTrue()
    {
        $sql = Qb::table('test_users')->unless(true, function (Qb $qb, $value) {
            $qb->where('name', $value);
        })->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users`', $sql);
    }

    public function testUnlessDefault()
    {
        $sql = Qb::table('test_users')->unless(true, function (Qb $qb, $value) {
            $qb->where('name', $value);
        }, function (Qb $qb, $value) {
            $qb->where('type', 0);
        })->getRawSql();

        $this->assertEquals('SELECT * FROM `p_test_users` WHERE `type` = 0', $sql);
    }

    public function testFetch()
    {
        $this->initFixtures();

        $data = Qb::table('test_users')->where('id', 1)->fetch();
        $this->assertIsArray($data);
        $this->assertEquals('1', $data['id']);
    }

    public function testFetchNoDataReturnsNull()
    {
        $this->initFixtures();

        $data = Qb::table('test_users')->where('id', -1)->fetch();
        $this->assertNull($data);
    }

    public function testFetchColumn()
    {
        $this->initFixtures();

        $result = Qb::table('test_users')->selectRaw('COUNT(id)')->fetchColumn();
        $this->assertSame('2', $result);

        $result = Qb::table('test_users')->where('id', -1)->fetchColumn();
        $this->assertNull($result);
    }

    public function testFetchAll()
    {
        $this->initFixtures();

        $data = Qb::table('test_users')->fetchAll();

        $this->assertIsArray($data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testFetchAllSnake()
    {
        $this->initFixtures();

        $data = Qb::setDbKeyConverter(null)
            ->setPhpKeyConverter(null)
            ->table('test_users')
            ->fetchAll();

        $this->assertIsArray($data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testFirst()
    {
        $this->initFixtures();

        $data = Qb::table('test_users')->where('id', 1)->first();
        $this->assertIsArray($data);
        $this->assertEquals('1', $data['id']);
    }

    public function testAll()
    {
        $this->initFixtures();

        $data = Qb::table('test_users')->all();

        $this->assertIsArray($data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testAllSnake()
    {
        $this->initFixtures();

        $data = Qb::setDbKeyConverter(null)
            ->setPhpKeyConverter(null)
            ->table('test_users')
            ->all();

        $this->assertIsArray($data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testIndexBy()
    {
        $this->initFixtures();

        $users = Qb::table('test_users')->indexBy('name')->fetchAll();

        $this->assertArrayHasKey('twin', $users);
        $this->assertArrayHasKey('test', $users);
    }

    public function testPluck()
    {
        $this->initFixtures();

        $ids = Qb::table('test_users')->pluck('id');

        $this->assertSame(['1', '2'], $ids);
    }

    public function testPluckWithIndex()
    {
        $this->initFixtures();

        $ids = Qb::table('test_users')->pluck('name', 'id');

        $this->assertSame([1 => 'twin', 2 => 'test'], $ids);
    }

    public function testCnt()
    {
        $this->initFixtures();

        $count = Qb::table('test_users')->cnt();
        $this->assertSame(2, $count);

        $count = Qb::table('test_users')->where('id', 1)->cnt();
        $this->assertSame(1, $count);
    }

    public function testCntIgnoreLimitOffset()
    {
        $this->initFixtures();

        $count = Qb::table('test_users')->limit(1)->offset(2)->cnt();

        $this->assertSame(2, $count);
    }

    public function testMax()
    {
        $this->initFixtures();

        $max = Qb::table('test_users')->max('id');

        $this->assertSame('2', $max);
    }

    public function testChunk()
    {
        $this->initFixtures();

        $this->db->batchInsert('test_users', [
            [
                'group_id' => '1',
                'name' => 'twin',
                'address' => 'test',
            ],
            [
                'group_id' => '1',
                'name' => 'twin',
                'address' => 'test',
            ],
        ]);

        $count = 0;
        $times = 0;
        $result = Qb::table('test_users')->chunk(2, static function ($data, $page) use (&$count, &$times) {
            $count += count($data);
            ++$times;
        });

        $this->assertEquals(4, $count);
        $this->assertEquals(2, $times);
        $this->assertTrue($result);
    }

    public function testUpdate()
    {
        $this->initFixtures();

        $row = Qb::table('test_users')->update(['address' => 'test address']);
        $this->assertEquals(2, $row);
        $this->assertSame('UPDATE `p_test_users` SET `address` = ?', $this->db->getLastQuery());

        $user = Qb::table('test_users')->where('id', 1)->first();
        $this->assertEquals('test address', $user['address']);
    }

    public function testUpdateKeyValue()
    {
        $this->initFixtures();

        $row = Qb::table('test_users')->update('address', 'test address');
        $this->assertEquals(2, $row);
        $this->assertSame('UPDATE `p_test_users` SET `address` = ?', $this->db->getLastQuery());
    }

    public function testUpdateWhere()
    {
        $this->initFixtures();

        $row = Qb::table('test_users')->where('id', 1)->update(['address' => 'test address']);
        $this->assertEquals(1, $row);
        $this->assertSame('UPDATE `p_test_users` SET `address` = ? WHERE `id` = ?', $this->db->getLastQuery());

        $user = Qb::table('test_users')->where('id', 1)->first();
        $this->assertEquals('test address', $user['address']);
    }

    public function testParameters()
    {
        $this->initFixtures();

        $query = Qb::table('test_users')
            ->whereRaw('id = :id AND group_id = :groupId')
            ->addQueryParam([
                'id' => 1,
                'groupId' => 1,
            ]);
        $user = $query->fetch();

        $this->assertEquals([
            'id' => 1,
            'groupId' => 1,
        ], $query->getBindParams());

        $this->assertEquals(1, $query->getQueryParam('id'));
        $this->assertNull($query->getQueryParam('no'));

        $this->assertEquals(1, $user['id']);
        $this->assertEquals(1, $user['group_id']);

        $query->setQueryParams([
            'id' => 10,
            'groupId' => 1,
        ]);
        $user = $query->first();
        $this->assertNull($user);
    }

    /**
     * @dataProvider providerForParameterValue
     * @param mixed $value
     */
    public function testParameterValue($value)
    {
        $this->initFixtures();

        $query = Qb::table('test_users')
            ->where('id', $value)
            ->where('id', '=', $value)
            ->orWhere('id', $value)
            ->orWhere('id', '=', $value)
            ->having('id', $value)
            ->having('id', '=', $value)
            ->orHaving('id', $value)
            ->orHaving('id', '=', $value);

        // No error raise
        $array = $query->fetchAll();
        $this->assertIsArray($array);
    }

    public function providerForParameterValue()
    {
        return [
            [null],
            ['0'],
            [0],
            [true],
            [[null]],
        ];
    }

    public function testGetAndResetAllSqlParts()
    {
        $query = Qb::table('test_users')->offset(1)->limit(1);

        $this->assertEquals(1, $query->getQueryPart('offset'));
        $this->assertEquals(1, $query->getQueryPart('limit'));

        $queryParts = $query->getQueryParts();
        $this->assertArrayHasKey('offset', $queryParts);
        $this->assertArrayHasKey('limit', $queryParts);

        $query->resetQueryParts();

        $this->assertNull($query->getQueryPart('offset'));
        $this->assertNull($query->getQueryPart('limit'));
    }

    public function testFromAlias()
    {
        $qb = Qb::from('test_users', 'm');
        $this->assertEquals('SELECT * FROM `p_test_users` `m`', $qb->getRawSql());

        $first = $qb->first();
        $this->assertNotNull($first);
    }

    public function testGetTable()
    {
        $query = Qb::table('test_users');

        $this->assertEquals('test_users', $query->getTable());

        $query->from('test_users', 'u');
        $this->assertEquals('test_users', $query->getTable());
    }

    public function testDeleteRecordByQueryBuilder()
    {
        $this->initFixtures();

        $result = Qb::table('test_users')->where('group_id', 1)->delete();
        $this->assertEquals(2, $result);

        $result = Qb::table('test_users')->delete(['group_id' => 1]);
        $this->assertEquals(0, $result);
    }

    public function testInvalidLimit()
    {
        $this->initFixtures();
        $user = Qb::table('test_users');

        $user->limit(-1);
        $this->assertEquals(1, $user->getQueryPart('limit'));

        $user->limit(0);
        $this->assertEquals(1, $user->getQueryPart('limit'));

        $user->limit('string');
        $this->assertEquals(1, $user->getQueryPart('limit'));
    }

    public function testInvalidOffset()
    {
        $this->initFixtures();
        $user = Qb::table('test_users');

        $user->offset(-1);
        $this->assertEquals(0, $user->getQueryPart('offset'));

        $user->offset(-1.1);
        $this->assertEquals(0, $user->getQueryPart('offset'));

        $user->offset('string');
        $this->assertEquals(0, $user->getQueryPart('offset'));

        $user->offset(9848519079999155811);
        $this->assertEquals(0, $user->getQueryPart('offset'));
    }

    public function testInvalidPage()
    {
        $this->initFixtures();
        $user = Qb::table('test_users');

        // @link http://php.net/manual/en/language.types.integer.php#language.types.integer.casting.from-float
        // (984851907999915581 - 1) * 10
        // => 9.8485190799992E+18
        // => (int)9.8485190799992E+18
        // => -8598224993710352384
        // => 0
        $user->page(984851907999915581);
        $this->assertEquals(0, $user->getQueryPart('offset'));
    }

    public function testJoin()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->join('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id');
        $this->assertSame(implode(' ', [
            'SELECT * FROM `p_test_users`',
            'INNER JOIN `p_test_user_groups` ON `p_test_users`.`group_id` = `p_test_user_groups`.`id`',
        ]), $qb->getRawSql());

        $user = $qb->fetch();
        $this->assertArrayHasKey('id', $user);
    }

    public function testJoinAlias()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users', 'u')->join('test_user_groups g', 'u.group_id', '=', 'g.id');
        $this->assertSame(
            'SELECT * FROM `p_test_users` `u` INNER JOIN `p_test_user_groups` `g` ON `u`.`group_id` = `g`.`id`',
            $qb->getRawSql()
        );

        $user = $qb->fetch();
        $this->assertArrayHasKey('id', $user);
    }

    public function testLeftJoin()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->leftJoin('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id');
        $this->assertSame(implode(' ', [
            'SELECT * FROM `p_test_users`',
            'LEFT JOIN `p_test_user_groups` ON `p_test_users`.`group_id` = `p_test_user_groups`.`id`',
        ]), $qb->getRawSql());

        $user = $qb->fetch();
        $this->assertArrayHasKey('id', $user);
    }

    public function testRightJoin()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->rightJoin('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id');
        $this->assertSame(implode(' ', [
            'SELECT * FROM `p_test_users`',
            'RIGHT JOIN `p_test_user_groups` ON `p_test_users`.`group_id` = `p_test_user_groups`.`id`',
        ]), $qb->getRawSql());

        $user = $qb->fetch();
        $this->assertArrayHasKey('id', $user);
    }

    public function testGetIdentifierConvert()
    {
        $fn = function () {
        };
        $qb = Qb::setDbKeyConverter($fn);

        $this->assertSame($fn, $qb->getDbKeyConverter());
    }

    public function testAutoAddTableNameToWhereWhenJoin()
    {
        $qb = Qb::table('test_users', 'u')
            ->where('id', 1)
            ->having('id', 1)
            ->join('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id');

        $this->assertSame(implode(' ', [
            'SELECT * FROM `p_test_users` `u`',
            'INNER JOIN `p_test_user_groups` ON `p_test_users`.`group_id` = `p_test_user_groups`.`id`',
            'WHERE `p_test_users`.`id` = 1',
            'HAVING `p_test_users`.`id` = 1',
        ]), $qb->getRawSql());
    }

    public function testResetQuery()
    {
        $qb = Qb::table('test_users')
            ->where('id', 1)
            ->having('id', 1);

        $qb->resetQuery();

        $queryParams = $qb->getQueryParams(null);
        $this->assertSame([
            'set' => [],
            'where' => [],
            'having' => [],
        ], $queryParams);
    }

    public function testSetQueryParam()
    {
        $this->initFixtures();

        $users = Qb::table('test_users')
            ->whereRaw('name = :name')
            ->setQueryParam('name', 'twin')
            ->fetchAll();
        $this->assertCount(1, $users);
        $this->assertSame('twin', $users[0]['name']);
    }

    public function testSetQueryParamTypes()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')
            ->whereRaw('group_id = :groupId AND name = :name', [
                'groupId' => 1,
                'name' => 'twin',
            ])
            ->setQueryParamTypes([
                'groupId' => PDO::PARAM_INT,
                'name' => PDO::PARAM_STR,
            ]);

        $data = $qb->fetch();
        $this->assertSame('twin', $data['name']);
        $this->assertSame('1', $data['group_id']);
    }

    /**
     * @group whereHas
     */
    public function testWhereHas()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'name' => 'hello',
        ]);
        $userId = $this->db->lastInsertId();

        $qb = Qb::table('test_users')->whereHas('name');
        $this->assertSame("SELECT * FROM `p_test_users` WHERE `name` != ''", $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $qb = Qb::table('test_users')->whereNotHas('name');
        $this->assertSame("SELECT * FROM `p_test_users` WHERE `name` = ''", $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereNotHas()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'name' => '',
        ]);
        $userId = $this->db->lastInsertId();

        $user = Qb::table('test_users')->whereNotHas('name')->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $user = Qb::table('test_users')->whereHas('name')->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereHasDefaultValue()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'signature' => 'hello',
        ]);
        $userId = $this->db->lastInsertId();

        $qb = Qb::table('test_users')->whereHas('signature');
        $this->assertSame("SELECT * FROM `p_test_users` WHERE `signature` != 'default'", $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $qb = Qb::table('test_users')->whereNotHas('signature');
        $this->assertSame("SELECT * FROM `p_test_users` WHERE `signature` = 'default'", $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereNotHasDefault()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'signature' => 'default',
        ]);
        $userId = $this->db->lastInsertId();

        $user = Qb::table('test_users')->whereNotHas('signature')->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $user = Qb::table('test_users')->whereHas('signature')->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereHasNull()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'birthday' => date('Y-m-d'),
        ]);
        $userId = $this->db->lastInsertId();

        $qb = Qb::table('test_users')->whereHas('birthday');
        $this->assertSame('SELECT * FROM `p_test_users` WHERE `birthday` IS NOT NULL', $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $qb = Qb::table('test_users')->whereNotHas('birthday');
        $this->assertSame('SELECT * FROM `p_test_users` WHERE `birthday` IS NULL', $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereNotHasNull()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'birthday' => null,
        ]);
        $userId = $this->db->lastInsertId();

        $user = Qb::table('test_users')->whereNotHas('birthday')->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $user = Qb::table('test_users')->whereHas('birthday')->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereHasDefaultAndNull()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'joined_date' => null,
        ]);
        $userId = $this->db->lastInsertId();

        $qb = Qb::table('test_users')->whereHas('joined_date');
        $this->assertSame(implode(' ', [
            'SELECT * FROM `p_test_users`',
            "WHERE (`joined_date` != '2000-01-01' OR `joined_date` IS NULL)",
        ]), $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $qb = Qb::table('test_users')->whereNotHas('joined_date');
        $this->assertSame("SELECT * FROM `p_test_users` WHERE `joined_date` = '2000-01-01'", $qb->getRawSql());

        $user = $qb->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    /**
     * @group whereHas
     */
    public function testWhereNotHasDefaultAndNull()
    {
        $this->initFixtures();

        $this->db->insert('test_users', [
            'joined_date' => '2000-01-01',
        ]);
        $userId = $this->db->lastInsertId();

        $user = Qb::table('test_users')->whereNotHas('joined_date')->desc('id')->first();
        $this->assertSame($userId, $user['id']);

        $user = Qb::table('test_users')->whereHas('joined_date')->desc('id')->first();
        $this->assertNotSame($userId, $user['id'] ?? null);
    }

    public function testGetRawSqlWithoutTable()
    {
        $sql = Qb::orderBy('x')->getRawSql();
        $this->assertSame('SELECT * FROM `p_` ORDER BY `x` ASC', $sql);
    }
}
