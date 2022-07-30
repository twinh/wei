<?php

declare(strict_types=1);

namespace WeiTest\Model;

use Wei\BaseModel;
use Wei\Logger;
use Wei\ModelTrait;
use WeiTest\Model\Fixture\TestUser;
use WeiTest\TestCase;

/**
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
class AttributeTest extends TestCase
{
    use Fixture\DbTrait;

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::dropTables();
    }

    public function testOffsetExists()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $this->assertTrue(isset($user['name']));
        $this->assertTrue(isset($user['group_id']));
        $this->assertFalse(isset($user['key2']));
    }

    public function testOffsetGet()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $this->assertSame('twin', $user['name']);
        $this->assertSame(0, $user['group_id']);
    }

    public function testOffsetGetInvalid()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: key'));
        // @phpstan-ignore-next-line
        $user['key'];
    }

    public function testOffsetSet()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $user['name'] = 'abc';
        $this->assertSame('abc', $user['name']);
    }

    public function testOffsetSetInvalid()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: key'));

        $user = TestUser::new();
        $user['key'] = 'test';
    }

    public function testOffsetUnset()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);
        $this->assertSame('twin', $user['name']);

        unset($user['name']);
        $this->assertSame('', $user['name']);
    }

    public function testGet()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);
        $this->assertSame('twin', $user->get('name'));
        $this->assertSame(0, $user->get('group_id'));
    }

    public function testGetInvalid()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: key2'));
        $this->assertNull($user->get('key2'));
    }

    public function testSet()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);
        $this->assertSame('twin', $user->get('name'));

        $user->set('group_id', 1);
        $this->assertSame(1, $user->get('group_id'));
    }

    public function testSetInvalid()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: key'));

        $user = TestUser::new([
            'name' => 'twin',
        ]);
        $user->set('key', 'test');
    }

    public function testSetModelAsColl()
    {
        $this->initFixtures();

        $user = TestUser::new();

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: [null]'));
        $user[] = TestUser::new();
    }

    public function testMagicGet()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);
        $this->assertSame('twin', $user->name);
        $this->assertSame(0, $user->group_id);
    }

    public function testMagicGetInvalid()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessageMatches(
            '/Property or object "key2" \(class "Wei\\\Key2"\) not found, called in file/'
        );

        // @phpstan-ignore-next-line
        $this->assertNull($user->key2);
    }

    public function testMagicSet()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $user->group_id = 1;
        $this->assertSame(1, $user->group_id);
    }

    public function testMagicSetInvalid()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'name' => 'twin',
        ]);

        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: key'));
        // @phpstan-ignore-next-line
        $user->key = 'abc';
    }

    public function testMagicUnset()
    {
        $this->initFixtures();

        $user = TestUser::new([
            'id' => 123,
        ]);
        $this->assertSame(123, $user->id);

        $user->id = null;
        $this->assertNull($user->id);
    }

    public function testMagicUnsetInvalid()
    {
        $this->initFixtures();
        $user = TestUser::new();
        $this->assertSame([], $user->getHidden());

        unset($user->hidden);
        $this->assertSame([], $user->getHidden());
    }

    public function testMagicIsset()
    {
        $this->initFixtures();

        $user = TestUser::new();

        $this->assertFalse(isset($user->id));

        $user->id = 123;
        $this->assertTrue(isset($user->id));

        $this->assertFalse(isset($user->notFound));
    }

    public function testMagicGetService()
    {
        $this->initFixtures();

        $user = TestUser::new();

        /** @phpstan-ignore-next-line */
        $logger = $user->logger;
        $this->assertInstanceOf(Logger::class, $logger);
    }

    /**
     * @group kk
     */
    public function testMagicSetService()
    {
        $this->initFixtures();

        $user = TestUser::new();
        /** @phpstan-ignore-next-line */
        $logger = $user->logger;

        $user = TestUser::new();
        // @phpstan-ignore-next-line
        $user->logger = $logger;

        $this->assertSame($logger, $user->logger);
    }

    /**
     * @group kk
     */
    public function testServiceNameAsColumn()
    {
        $schema = wei()->schema;

        $schema->dropIfExists('test_services');
        $schema->table('test_services')
            ->string('db')
            ->string('str')
            ->string('cls')
            ->string('cache')
            ->string('event')
            ->exec();

        $service = new class () extends BaseModel {
            use ModelTrait;

            protected $primaryKey = 'db';

            protected $table = 'test_services';
        };

        foreach ($service->getColumns() as $column => $config) {
            $service->{$column} = $column;
        }

        foreach ($service->getColumns() as $column => $config) {
            $this->assertSame($column, $service->{$column});
        }

        $service->save();

        foreach ($service->getColumns() as $column => $config) {
            $this->assertSame($column, $service->{$column});
        }

        $schema->dropIfExists('test_services');
    }
}
