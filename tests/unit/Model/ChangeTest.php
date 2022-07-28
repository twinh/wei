<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\DbTrait;
use WeiTest\Model\Fixture\TestUser;
use WeiTest\TestCase;

/**
 * @group change
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
class ChangeTest extends TestCase
{
    use DbTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::setTablePrefix('p_');
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::dropTables();
        static::resetTablePrefix();
    }

    public function testNewModel()
    {
        $this->initFixtures();

        $user = TestUser::new();
        $this->assertFalse($user->isChanged());

        $user->name = 'test';
        $this->assertTrue($user->isChanged('name'));
        $this->assertFalse($user->isChanged('group_id'));
        $this->assertTrue($user->isChanged());
        $this->assertSame('', $user->getChanges('name'));
        $this->assertSame(['name' => ''], $user->getChanges());

        $user->name = 'abc';
        $this->assertTrue($user->isChanged('name'));
        $this->assertFalse($user->isChanged('group_id'));
        $this->assertTrue($user->isChanged());
        $this->assertSame('', $user->getChanges('name'));
        $this->assertSame(['name' => ''], $user->getChanges());
    }

    public function testExistModel()
    {
        $this->initFixtures();

        $user = TestUser::findBy(['name' => 'test']);
        $this->assertFalse($user->isChanged());

        $user->name = 'test2';
        $this->assertTrue($user->isChanged());
        $this->assertSame('test', $user->getChanges('name'));

        $user->name = 'abc';
        $this->assertSame('test', $user->getChanges('name'));
    }

    public function testChangeBack()
    {
        $this->initFixtures();

        $user = TestUser::findBy(['name' => 'test']);
        $this->assertFalse($user->isChanged());

        $user->name = 'test2';
        $this->assertTrue($user->isChanged());
        $this->assertSame('test', $user->getChanges('name'));

        $user->name = 'test';
        $this->assertNull($user->getChanges('name'));
    }

    public function testSameValue()
    {
        $this->initFixtures();

        $user = TestUser::new();

        $user->name = '';
        $this->assertFalse($user->isChanged('name'));
        $this->assertFalse($user->isChanged());

        $user->name = null;
        $this->assertFalse($user->isChanged('name'));
        $this->assertFalse($user->isChanged());
    }

    public function testSave()
    {
        $this->initFixtures();

        $user = TestUser::new();
        $this->assertFalse($user->isChanged());

        $user->name = 'tt';
        $user->group_id = 1;
        $user->address = 'address';
        $this->assertFalse($user->isChanged('id'));
        $this->assertTrue($user->isChanged('name'));
        $this->assertTrue($user->isChanged());
        $this->assertSame([
            'name' => '',
            'group_id' => 0,
            'address' => 'default address',
        ], $user->getChanges());

        $this->assertSame('', $user->getChanges('name'));

        $user->name = 'aa';
        $this->assertTrue($user->isChanged());
        $this->assertSame('', $user->getChanges('name'));

        $user->save();
        $this->assertFalse($user->isChanged());
        $this->assertEmpty($user->getChanges());
    }

    public function testSaveOnNoAttributeChanged()
    {
        $this->initFixtures();

        $user = TestUser::new();
        $result = $user->save();

        $this->assertInstanceOf(TestUser::class, $result);
    }

    public function testUpdate()
    {
        $this->initFixtures();

        $user = TestUser::save();

        $user->name = 'test';
        $this->assertTrue($user->isChanged('name'));

        $user->save();
        $this->assertSame('test', $user->name);

        // Save again, no column change, wont execute update
        $queryCount = count(wei()->db->getQueries());
        $user->save();
        $this->assertCount($queryCount, wei()->db->getQueries());
    }
}
