<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestGetSet;
use WeiTest\TestCase;

/**
 * @internal
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
final class GetSetTraitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_get_sets')
            ->id('id')
            ->string('name')
            ->int('user_count')
            ->exec();

        wei()->db->batchInsert('test_get_sets', [
            [
                'id' => 1,
                'name' => 'abc',
            ],
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_get_sets');
    }

    public function testIsset()
    {
        $test = TestGetSet::new();
        $test->id = 2;

        $this->assertTrue(isset($test['id']));
        $this->assertSame('', $test->name);

        $this->assertTrue(isset($test->id));

        // 可直接判断
        $this->assertTrue((bool) $test->id);
    }

    public function testGetIdBecomeNull()
    {
        $test = TestGetSet::new();
        // receive id
        // @phpstan-ignore-next-line
        $test->id;

        $test->save();

        $this->assertNotNull($test->id);

        $this->assertIsInt($test->id);
    }

    public function testSaveIdShouldBeInt()
    {
        $test = TestGetSet::new();

        $test->save();

        $this->assertIsInt($test->id);
    }

    public function testIndexBy()
    {
        $tests = TestGetSet::indexBy('name')
            ->findAllBy('name', 'abc');

        $this->assertEquals('abc', $tests['abc']->name);
    }

    /**
     * @group change
     */
    public function testIncrSave()
    {
        $getSet = TestGetSet::new();
        $getSet->incrSave('user_count', 2);
        $this->assertEquals(2, $getSet->user_count);
        $this->assertFalse($getSet->isChanged('user_count'));
        $this->assertFalse($getSet->isChanged());

        $getSet->incrSave('user_count', 3);
        $getSet->name = 'test';
        $this->assertEquals(5, $getSet->user_count);
        $this->assertFalse($getSet->isChanged('user_count'));
        $this->assertTrue($getSet->isChanged('name'));
        $this->assertTrue($getSet->isChanged());
    }

    public function testDecrSave()
    {
        $getSet = TestGetSet::new();
        $getSet->decrSave('user_count', 2);
        $this->assertEquals(-2, $getSet->user_count);
        $this->assertFalse($getSet->isChanged('user_count'));
        $this->assertFalse($getSet->isChanged());

        $getSet->decrSave('user_count', 3);
        $getSet->name = 'test';
        $this->assertEquals(-5, $getSet->user_count);
        $this->assertFalse($getSet->isChanged('user_count'));
        $this->assertTrue($getSet->isChanged('name'));
        $this->assertTrue($getSet->isChanged());
    }
}
