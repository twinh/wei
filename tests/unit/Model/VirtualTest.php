<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestVirtual;
use WeiTest\TestCase;

/**
 * @internal
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
final class VirtualTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_virtual')
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
        wei()->schema->dropIfExists('test_virtual');
    }

    public function testGetNew()
    {
        $virtual = TestVirtual::new();

        $this->assertNull($virtual->getVirtualColumnValue());
        $this->assertNull($virtual['virtual_column']);
        $this->assertNull($virtual->virtual_column);
        $this->assertNull($virtual->get('virtual_column'));
    }

    public function testGetAfterSet()
    {
        $virtual = TestVirtual::new();

        $virtual->virtual_column = 'something';

        $this->assertEquals('something', $virtual->getVirtualColumnValue());
        $this->assertEquals('something', $virtual['virtual_column']);
        $this->assertEquals('something', $virtual->virtual_column);
        $this->assertEquals('something', $virtual->get('virtual_column'));
    }

    public function testOffsetSet()
    {
        $virtual = TestVirtual::new();

        $virtual['virtual_column'] = 'something';

        $this->assertEquals('something', $virtual->getVirtualColumnValue());
        $this->assertEquals('something', $virtual['virtual_column']);
        $this->assertEquals('something', $virtual->virtual_column);
        $this->assertEquals('something', $virtual->get('virtual_column'));
    }

    public function testSetMethod()
    {
        $virtual = TestVirtual::new();

        $virtual->set('virtual_column', 'something');

        $this->assertEquals('something', $virtual->getVirtualColumnValue());
        $this->assertEquals('something', $virtual['virtual_column']);
        $this->assertEquals('something', $virtual->virtual_column);
        $this->assertEquals('something', $virtual->get('virtual_column'));
    }

    public function testGetFullName()
    {
        $virtual = TestVirtual::new();

        $virtual->first_name = 'Hello';
        $virtual->last_name = 'World';

        $this->assertEquals('Hello World', $virtual->full_name);
    }

    public function testSetFullName()
    {
        $virtual = TestVirtual::new();

        $virtual->full_name = 'Hello World';

        $this->assertEquals('Hello', $virtual->first_name);
        $this->assertEquals('World', $virtual->last_name);
    }

    public function testGetCamelCaseThrowsException()
    {
        $virtual = TestVirtual::new();
        $this->expectExceptionMessage('Property or object "virtualColumn" (class "Wei\VirtualColumn") not found');
        // @phpstan-ignore-next-line
        $virtual->virtualColumn;
    }

    public function testSetCamelCaseThrowsException()
    {
        $virtual = TestVirtual::new();
        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: virtualColumn'));
        // @phpstan-ignore-next-line
        $virtual->virtualColumn = 'abc';
    }

    public function testMissingSetVirtualAttributeMethod()
    {
        $virtual = TestVirtual::new();

        $virtual->fromArray([
            'only_get' => 'test2',
        ]);

        $this->assertSame('test', $virtual->get('only_get'));
    }
}
