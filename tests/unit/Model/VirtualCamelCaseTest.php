<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestVirtualCamelCase;
use WeiTest\TestCase;

/**
 * @internal
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
final class VirtualCamelCaseTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_virtual_camel_cases')
            ->id()
            ->string('first_name')
            ->string('last_name')
            ->exec();
    }

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_virtual_camel_cases');
    }

    public function testGetNew()
    {
        $virtual = TestVirtualCamelCase::new();

        $this->assertNull($virtual->getVirtualColumnValue());
        $this->assertNull($virtual['virtualColumn']);
        $this->assertNull($virtual->virtualColumn);
        $this->assertNull($virtual->get('virtualColumn'));
    }

    public function testGetAfterSet()
    {
        $virtual = TestVirtualCamelCase::new();

        $virtual->virtualColumn = 'something';

        $this->assertEquals('something', $virtual->getVirtualColumnValue());
        $this->assertEquals('something', $virtual['virtualColumn']);
        $this->assertEquals('something', $virtual->virtualColumn);
        $this->assertEquals('something', $virtual->get('virtualColumn'));
    }

    public function testOffsetSet()
    {
        $virtual = TestVirtualCamelCase::new();

        $virtual['virtualColumn'] = 'something';

        $this->assertEquals('something', $virtual->getVirtualColumnValue());
        $this->assertEquals('something', $virtual['virtualColumn']);
        $this->assertEquals('something', $virtual->virtualColumn);
        $this->assertEquals('something', $virtual->get('virtualColumn'));
    }

    public function testSetMethod()
    {
        $virtual = TestVirtualCamelCase::new();

        $virtual->set('virtualColumn', 'something');

        $this->assertEquals('something', $virtual->getVirtualColumnValue());
        $this->assertEquals('something', $virtual['virtualColumn']);
        $this->assertEquals('something', $virtual->virtualColumn);
        $this->assertEquals('something', $virtual->get('virtualColumn'));
    }

    public function testGetFullName()
    {
        $virtual = TestVirtualCamelCase::new();

        $virtual->firstName = 'Hello';
        $virtual->lastName = 'World';

        $this->assertEquals('Hello World', $virtual->fullName);
    }

    public function testSetFullName()
    {
        $virtual = TestVirtualCamelCase::new();

        $virtual->fullName = 'Hello World';

        $this->assertEquals('Hello', $virtual->firstName);
        $this->assertEquals('World', $virtual->lastName);
    }

    public function testGetSnakeCaseThrowsException()
    {
        $virtual = TestVirtualCamelCase::new();
        $this->expectExceptionMessage('Property or object "virtual_column" (class "Wei\Virtual_column") not found');
        // @phpstan-ignore-next-line
        $virtual->virtual_column;
    }

    public function testSetSnakeCaseThrowsException()
    {
        $virtual = TestVirtualCamelCase::new();
        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: virtual_column'));
        $virtual->virtual_column = 'abc';
    }

    public function testToArray()
    {
        $virtual = TestVirtualCamelCase::new();
        $array = $virtual->toArray(['id']);
        $this->assertSame(['id'], array_keys($array));
    }

    public function testToArrayWithVirtualColumns()
    {
        $virtual = TestVirtualCamelCase::new();
        $array = $virtual->toArray(['virtualColumn']);
        $this->assertSame(['virtualColumn'], array_keys($array));
    }
}
