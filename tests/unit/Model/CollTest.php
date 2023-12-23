<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Fixtures\DbTrait;
use WeiTest\Model\Fixture\TestUser;
use WeiTest\TestCase;

/**
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
class CollTest extends TestCase
{
    use DbTrait;

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::dropTables();
    }

    public function testOffsetExists()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);
        $this->assertTrue(isset($users[0]));
        $this->assertFalse(isset($users[1]));

        $this->assertTrue(isset($users['key']));
        $this->assertFalse(isset($users['key2']));
    }

    public function testOffsetGet()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);
        $this->assertInstanceOf(TestUser::class, $users[0]);
        $this->assertInstanceOf(TestUser::class, $users['key']);
    }

    public function testOffsetGetInvalid()
    {
        $this->initFixtures();

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: 0'));
        $users = TestUser::newColl();
        // @phpstan-ignore-next-line
        $users[0];
    }

    public function testOffsetSet()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $users['key'] = TestUser::new();
        $this->assertArrayHasKey('key', $users);

        $users[] = TestUser::new();
        $this->assertSame(['key', 0], array_keys($users->toArray()));
    }

    public function testOffsetSetInvalid()
    {
        $this->expectException(\TypeError::class);

        $users = TestUser::newColl();
        $users['key'] = 'test';
    }

    public function testOffsetUnset()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);
        $this->assertSame([0, 'key'], array_keys($users->toArray()));

        unset($users[0]);
        $this->assertSame(['key'], array_keys($users->toArray()));

        unset($users['key']);
        $this->assertSame([], array_keys($users->toArray()));
    }

    public function testGet()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);
        $this->assertInstanceOf(TestUser::class, $users->get(0));

        $this->assertInstanceOf(TestUser::class, $users->get('key'));
    }

    public function testGetInvalid()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: key2'));
        $this->assertNull($users->get('key2'));
    }

    public function testSet()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $users->set('key', TestUser::new());
        $this->assertArrayHasKey('key', $users);

        $users->set(0, TestUser::new());
        $this->assertSame(['key', 0], array_keys($users->toArray()));

        $users->set(null, TestUser::new());
        $this->assertSame(['key', 0, 1], array_keys($users->toArray()));
    }

    public function testSetInvalid()
    {
        $this->expectException(\TypeError::class);

        $users = TestUser::newColl();
        $users->set('key', 'test');
    }

    public function testMagicIsset()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);

        $this->assertTrue(isset($users->{0}));
        $this->assertFalse(isset($users->{1}));

        $this->assertTrue(isset($users->key));
        $this->assertFalse(isset($users->key2));
    }

    public function testMagicGet()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);
        $this->assertInstanceOf(TestUser::class, $users->{0});
        // @phpstan-ignore-next-line
        $this->assertInstanceOf(TestUser::class, $users->key);
    }

    public function testMagicGetInvalid()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessageMatches(
            '/Property or object "key2" \(class "Wei\\\Key2"\) not found, called in file/'
        );
        // @phpstan-ignore-next-line
        $this->assertNull($users->key2);
    }

    public function testMagicSet()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        // @phpstan-ignore-next-line
        $users->key = TestUser::new();
        $this->assertArrayHasKey('key', $users);

        $users->{0} = TestUser::new();
        $this->assertSame(['key', 0], array_keys($users->toArray()));

        $users->{null} = TestUser::new();
        $this->assertSame(['key', 0, ''], array_keys($users->toArray()));
    }

    public function testMagicUnset()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            TestUser::new(),
            'key' => TestUser::new(),
        ]);
        $this->assertSame([0, 'key'], array_keys($users->toArray()));

        unset($users->{0});
        $this->assertSame(['key'], array_keys($users->toArray()));

        unset($users->key);
        $this->assertSame([], array_keys($users->toArray()));
    }

    public function testResult()
    {
        $this->initFixtures();

        $users = TestUser::all();

        $this->assertInstanceOf(TestUser::class, $users);

        $userArray = $users->toArray();
        $this->assertIsArray($userArray);
        foreach ($userArray as $user) {
            $this->assertIsArray($user);
        }
    }

    public function testFilter()
    {
        $this->initFixtures();

        $users = TestUser::all();

        $oneUsers = $users->filter(static function (TestUser $user) {
            return 1 === $user->id;
        });

        $this->assertCount(1, $oneUsers);
        $this->assertEquals(1, $oneUsers[0]->id);

        $noMembers = $users->filter(static function () {
            return false;
        });

        $this->assertCount(0, $noMembers);
        $this->assertEmpty($noMembers->toArray());
    }

    public function testAddNotModelToCollection()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $this->expectException(\TypeError::class);

        // Assign non record value to raise an exception
        $users[] = 234;
    }

    public function testFindCollectionAndDestroy()
    {
        $this->initFixtures();

        $users = TestUser::findAllBy('group_id', 1);
        $users->destroy();

        $users = TestUser::findAllBy('group_id', 1);
        $this->assertCount(0, $users);
    }

    public function testFindAndUpdate()
    {
        $this->initFixtures();

        $user = TestUser::find(1);
        $user->name = 'William';
        $result = $user->save();
        $this->assertSame($result, $user);

        $user = TestUser::find(1);
        $this->assertEquals('William', $user->name);
    }

    public function testFindCollectionAndUpdate()
    {
        $this->initFixtures();

        $users = TestUser::findAllBy('group_id', 1);

        $this->assertCount(2, $users);

        foreach ($users as $user) {
            $user->group_id = 2;
        }
        $users->save();

        $users = TestUser::findAllBy('group_id', 2);
        $this->assertCount(2, $users);
    }

    public function testCreateCollectionAndSave()
    {
        $this->initFixtures();

        // Creates a user collection
        $users = TestUser::newColl();

        $john = TestUser::fromArray([
            'group_id' => 2,
            'name' => 'John',
            'address' => 'xx street',
        ]);

        $larry = TestUser::fromArray([
            'group_id' => 3,
            'name' => 'Larry',
            'address' => 'xx street',
        ]);

        // Adds record to collection
        $users->fromArray([
            $john,
        ]);

        // Or adds by [] operator
        $users[] = $larry;

        $result = $users->save();

        $this->assertSame($result, $users);

        // Find out member by id
        $users = TestUser::indexBy('id')->whereIn('id', [$john['id'], $larry['id']])->all();

        $this->assertEquals('John', $users[$john['id']]['name']);
        $this->assertEquals('Larry', $users[$larry['id']]['name']);
    }

    public function testNullAsCollectionKey()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $users[] = TestUser::new();
        $users[] = TestUser::new();
        $users[] = TestUser::new();
        $users[] = TestUser::new();

        $this->assertCount(4, $users);
    }

    public function testIndexBy()
    {
        $this->initFixtures();

        $users = TestUser::all();

        $users = $users->indexBy('name')->toArray();

        $this->assertArrayHasKey('twin', $users);
        $this->assertArrayHasKey('test', $users);
    }

    public function testToArray()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            'key1' => TestUser::new(),
            'key2' => TestUser::new(),
        ]);

        $users = $users->toArray();
        $this->assertSame([
            'key1' => [
                'id' => null,
                'group_id' => 0,
                'name' => '',
                'is_admin' => false,
                'address' => 'default address',
                'birthday' => null,
                'joined_date' => '2000-01-01',
                'signature' => 'default',
            ],
            'key2' => [
                'id' => null,
                'group_id' => 0,
                'name' => '',
                'is_admin' => false,
                'address' => 'default address',
                'birthday' => null,
                'joined_date' => '2000-01-01',
                'signature' => 'default',
            ],
        ], $users);
    }

    public function testToArrayWithSpecifyColumns()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            'key1' => TestUser::new(),
        ]);

        $users = $users->toArray(['id', 'name']);
        $this->assertSame([
            'key1' => [
                'id' => null,
                'name' => '',
            ],
        ], $users);
    }

    public function testToArrayWithPrependColumns()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            'key1' => TestUser::new(),
        ]);

        $users = $users->toArray(static function (TestUser $model) {
            return [
                'test' => $model->address,
            ];
        });
        $this->assertSame([
            'key1' => [
                'test' => 'default address',
                'id' => null,
                'group_id' => 0,
                'name' => '',
                'is_admin' => false,
                'address' => 'default address',
                'birthday' => null,
                'joined_date' => '2000-01-01',
                'signature' => 'default',
            ],
        ], $users);
    }

    public function testToArrayWithSpecifyColumnsAndPrependColumns()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            'key1' => TestUser::new(),
        ]);

        $users = $users->toArray(['id', 'name'], static function (TestUser $model) {
            return [
                'test' => $model->address,
            ];
        });
        $this->assertSame([
            'key1' => [
                'test' => 'default address',
                'id' => null,
                'name' => '',
            ],
        ], $users);
    }

    public function testToArrayKeepKeys()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            'key1' => TestUser::new(),
            'key2' => TestUser::new(),
        ]);

        $users = $users->toArray();

        $this->assertArrayHasKey('key1', $users);
        $this->assertArrayHasKey('key2', $users);
    }

    public function testIndexByMultipleTimes()
    {
        $this->initFixtures();

        $users = TestUser::indexBy('id')->all();

        $this->assertArrayHasKey(1, $users);

        $users = $users->indexBy('name');
        $this->assertArrayHasKey('twin', $users);

        $users = $users->indexBy('id');
        $this->assertArrayHasKey(1, $users);
    }

    public function testToArrayWithPrepend()
    {
        $this->initFixtures();

        $users = TestUser::limit(1)->all();

        $data = $users->toArray(static function (TestUser $user) {
            return [
                'newId' => $user->id + 1,
            ];
        });
        $this->assertSame(2, $data[0]['newId']);
        $this->assertArrayHasKey('id', $data[0]);

        $data = $users->toArray(['name'], static function ($user) {
            return [
                'newId' => $user->id + 1,
            ];
        });
        $this->assertSame(2, $data[0]['newId']);
        $this->assertArrayNotHasKey('id', $data[0]);
    }

    public function testCount()
    {
        $this->initFixtures();

        $users = TestUser::limit(1)->all();

        $this->assertCount(1, $users);
    }

    public function testForEach()
    {
        $this->initFixtures();

        $users = TestUser::where('group_id', 1)->all();
        foreach ($users as $user) {
            $this->assertEquals(1, $user['group_id']);
        }
    }

    public function testDestroy()
    {
        $this->initFixtures();

        $users = TestUser::findAll([1, 2]);
        $this->assertCount(2, $users);

        $users->destroy();

        $users = TestUser::findAll([1, 2]);
        $this->assertCount(0, $users);
    }

    public function testSave()
    {
        $this->initFixtures();

        $users = TestUser::newColl([
            $user1 = TestUser::new(),
            $user2 = TestUser::new(),
        ]);

        $users->save();
        $this->assertFalse($user1->isNew());
        $this->assertFalse($user2->isNew());
    }

    public function testFilterCallByModel()
    {
        $this->expectExceptionObject(new \BadMethodCallException(
            'Method "filter" can be called when the object is a collection'
        ));

        $user = TestUser::new();
        $user->filter(static function () {
        });
    }

    public function testNewCollWithEmptyAttributes()
    {
        $this->initFixtures();

        $users = TestUser::newColl();

        $this->assertEmpty($users->toArray());
    }

    public function testNewModelWontChangeToColl()
    {
        $this->initFixtures();

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: 0'));

        TestUser::new([
            TestUser::new(),
        ]);
    }

    public function testSerialize()
    {
        if (version_compare(\PHP_VERSION, '7.4.0', '<')) {
            $this->markTestSkipped('Serialize require PHP 7.4+');
        }

        $this->initFixtures();

        $users = TestUser::new()->limit(2)->all();

        $data = serialize($users);

        $users2 = unserialize($data);
        $this->assertSame($users->count(), $users2->count());

        $sql = $users2->getSql();
        $this->assertSqlSame('SELECT * FROM `test_users` WHERE `id` IN (?, ?)', $sql);
    }

    protected function assertSqlSame($expected, $actual, string $message = '')
    {
        $this->assertSame($expected, str_replace($this->db->getTablePrefix(), '', $actual), $message);
    }
}
