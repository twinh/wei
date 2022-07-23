<?php

declare(strict_types=1);

namespace WeiTest;

use Wei\QueryBuilder as Qb;
use WeiTest\Fixtures\DbTrait;

class QueryBuilderCacheTest extends TestCase
{
    use DbTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::setTablePrefix('p_');
    }

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        static::resetTablePrefix();
        parent::tearDownAfterClass();
    }

    public function testCacheTags()
    {
        $this->initFixtures();

        $getUser = static function () {
            return Qb::table('test_users')->setCacheTags()->where('id', 1)->first();
        };

        $user = $getUser();
        $this->assertEquals('twin', $user['name']);

        $rows = Qb::table('test_users')->where('name', 'twin')->update(['name' => 'twin2']);
        $this->assertSame(1, $rows);

        $user = $getUser();
        $this->assertEquals('twin', $user['name']);

        Qb::table('test_users')->clearTagCache();

        $user = $getUser();
        $this->assertEquals('twin2', $user['name']);

        wei()->cache->clear();
    }

    public function testCacheTagsWithJoin()
    {
        $this->initFixtures();

        $user = Qb::table('test_users')
            ->select('test_users.*')
            ->leftJoin('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id')
            ->where('test_users.id', 1)
            ->setCacheTags();

        // Fetch from db
        $data = $user->fetch();
        $this->assertEquals('twin', $data['name']);

        Qb::table('test_users')->where('id', 1)->update('name', 'twin2');

        // Fetch from cache
        $data = $user->fetch();
        $this->assertEquals('twin', $data['name']);

        // Clear cache
        wei()->tagCache('test_users')->clear();
        wei()->tagCache('test_users', 'test_user_groups')->reload();

        // Fetch from db
        $data = $user->fetch();
        $this->assertEquals('twin2', $data['name']);
    }

    public function testCustomCacheTags()
    {
        $this->initFixtures();

        $user = Qb::table('test_users')
            ->select('test_users.*')
            ->leftJoin('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id')
            ->where('test_users.id', 1)
            ->setCacheTags(['users', 'user_groups']);

        // Fetch from db
        $data = $user->fetch();
        $this->assertEquals('twin', $data['name']);

        Qb::table('test_users')->where('id', 1)->update(['name' => 'twin2']);

        // Fetch from cache
        $data = $user->fetch();
        $this->assertEquals('twin', $data['name']);

        // Clear cache
        wei()->tagCache('users')->clear();
        wei()->tagCache('users', 'user_groups')->reload();

        // Fetch from db
        $data = $user->fetch();
        $this->assertEquals('twin2', $data['name']);

        wei()->cache->clear();
    }

    public function testCacheKey()
    {
        $this->initFixtures();

        $user = Qb::table('test_users')->setCacheKey('member-1')->where('id', 1)->first();

        $this->assertEquals(1, $user['id']);

        $cacheData = wei()->cache->get('member-1');
        $this->assertEquals('1', $cacheData[0]['id']);

        wei()->cache->clear();
    }

    public function testGetSetCacheKey()
    {
        $user = Qb::table('test_users')->where('id', 1);
        $this->assertNotEmpty($user->getCacheKey());

        $user2 = Qb::table('test_users')->where('id', 1);
        $this->assertSame($user->getCacheKey(), $user2->getCacheKey());

        $user2->setCacheKey('user-1');
        $this->assertSame('user-1', $user2->getCacheKey());
    }

    public function testGetSetCacheTime()
    {
        $user = Qb::table('test_users');

        $this->assertNotEmpty($user->getCacheTime());

        $user->setCacheTime(0);
        $this->assertSame(0, $user->getCacheTime());

        $user->setCacheTime(10);
        $this->assertSame(10, $user->getCacheTime());
    }

    public function testGetSetCacheTags()
    {
        $user = Qb::table('test_users');
        $this->assertNull($user->getCacheTags());

        $user->setCacheTags();
        // @phpstan-ignore-next-line invalid check
        $this->assertSame(['test_users'], $user->getCacheTags());

        $user->join('test_user_groups', 'test_users.group_id', '=', 'test_user_groups.id');
        // @phpstan-ignore-next-line invalid check
        $this->assertSame(['test_users', 'test_user_groups'], $user->getCacheTags());

        $user->setCacheTags(['tag1', 'tag2']);
        // @phpstan-ignore-next-line invalid check
        $this->assertSame(['tag1', 'tag2'], $user->getCacheTags());
    }
}
