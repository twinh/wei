<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestUser;
use WeiTest\TestCase;

class CacheTest extends TestCase
{
    use Fixture\DbTrait;

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

    public function testGetModelCacheKey()
    {
        $this->initFixtures();

        $user = TestUser::new();

        $key = $user->getModelCacheKey(1);
        $this->assertStringContainsString('1', $key);

        $key2 = $user->getModelCacheKey('abc');
        $this->assertStringContainsString('abc', $key2);

        $this->assertNotSame($key, $key2);
    }

    public function testRemoveModelCache()
    {
        $this->initFixtures();

        $user = TestUser::save(['name' => 'user']);
        $user->removeModelCache();

        $user2 = $this->findUserFromCache($user->id);
        $this->assertSame($user->id, $user2->id);

        $user2->save(['name' => 'user2']);
        $user3 = $this->findUserFromCache($user->id);
        $this->assertSame('user', $user3->name);

        $user3->removeModelCache();
        $user4 = $this->findUserFromCache($user->id);
        $this->assertSame('user2', $user4->name);
    }

    protected function findUserFromCache($id)
    {
        $user = TestUser::new();
        return $user
            ->setCacheKey($user->getModelCacheKey($id))
            ->find($id);
    }
}
