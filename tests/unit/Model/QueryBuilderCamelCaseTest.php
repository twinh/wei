<?php

declare(strict_types=1);

namespace WeiTest\Model;

use Wei\QueryBuilder as Qb;
use Wei\Str;
use WeiTest\Fixtures\DbTrait;
use WeiTest\TestCase;

class QueryBuilderCamelCaseTest extends TestCase
{
    use DbTrait;

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->wei->setConfig('queryBuilder', [
            'dbKeyConverter' => [Str::class, 'snake'],
            'phpKeyConverter' => [Str::class, 'camel'],
        ]);
    }

    public function testResult()
    {
        $this->initFixtures();

        $data = Qb::table('test_users')->first();

        $this->assertIsArray($data);
        $this->assertEquals('1', $data['groupId']);
        $this->assertArrayNotHasKey('group_id', $data);
    }

    public function testQueryParts()
    {
        $this->initFixtures();

        $qb = Qb::table('testUsers')->where('groupId', 1);

        $this->assertSame('SELECT * FROM `p_test_users` WHERE `group_id` = 1', $qb->getRawSql());

        $this->assertSame('1', $qb->fetch()['groupId']);
    }

    public function testAllowSnakeCaseQueryParts()
    {
        $this->initFixtures();

        $qb = Qb::table('test_users')->where('group_id', 1);

        $this->assertSame('SELECT * FROM `p_test_users` WHERE `group_id` = 1', $qb->getRawSql());

        $this->assertSame('1', $qb->fetch()['groupId']);
    }
}
