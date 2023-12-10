<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestMutator;
use WeiTest\TestCase;

/**
 * @internal
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
final class MutatorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::dropTables();

        $table = 'test_mutators';
        wei()->schema->table($table)
            ->id()
            ->string('getter')
            ->string('setter')
            ->string('mutator')
            ->string('default_value')
            ->string('object')
            ->exec();

        wei()->db->batchInsert($table, [
            [
                'getter' => base64_encode('getter'),
                'setter' => base64_encode('setter'),
                'mutator' => base64_encode('mutator'),
                'object' => '{}',
            ],
            [
                'getter' => base64_encode('getter'),
                'setter' => base64_encode('setter'),
                'mutator' => base64_encode('mutator'),
                'object' => '{}',
            ],
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        self::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_mutators');
    }

    public function testGet()
    {
        $mutator = TestMutator::find(1);

        $this->assertEquals('getter', $mutator->get('getter'));
    }

    public function testSet()
    {
        $mutator = TestMutator::new();
        $mutator->set('setter', 'abc');
        $this->assertEquals('YWJj', $mutator->setter, '由mutator管理数据');
    }

    public function testMagicGet()
    {
        $this->assertEquals('getter', TestMutator::find(1)->getter);
    }

    public function testMagicSet()
    {
        $mutator = TestMutator::new();
        $mutator->setter = 'abc';
        $this->assertEquals('YWJj', $mutator->setter);
    }

    public function testCreate()
    {
        $mutator = TestMutator::new();
        $mutator->setter = 'abc';

        $mutator->save();

        $data = wei()->db->select($mutator->getTable(), ['id' => $mutator->id]);
        $this->assertEquals(base64_encode('abc'), $data['setter']);
    }

    public function testUpdate()
    {
        $mutator = TestMutator::find(1);
        $mutator->setter = 'bbc';
        $mutator->save();

        $data = wei()->db->select($mutator->getTable(), ['id' => $mutator->id]);
        $this->assertEquals(base64_encode('bbc'), $data['setter']);

        $this->assertSame(base64_encode('bbc'), $mutator->setter);
    }

    /**
     * @group change
     */
    public function testChange()
    {
        $mutator = TestMutator::find(2);

        $setterValue = $mutator->setter;
        $this->assertSame(base64_encode('setter'), $setterValue);
        $mutator->setter = 'abc';
        $this->assertTrue($mutator->isChanged('setter'));
        $this->assertSame($setterValue, $mutator->getChanges('setter'));

        $getterValue = $mutator->getter;
        $this->assertSame('getter', $getterValue);
        $mutator->getter = base64_encode('bbc');
        $this->assertTrue($mutator->isChanged('getter'));
        $this->assertSame($getterValue, $mutator->getChanges('getter'));

        $mutatorValue = $mutator->mutator;
        $this->assertSame('mutator', $mutatorValue);
        $mutator->mutator = base64_encode('abc');
        $this->assertTrue($mutator->isChanged('mutator'));
        $this->assertSame($mutatorValue, $mutator->getChanges('mutator'));

        $objectValue = $mutator->object;
        $this->assertEquals($objectValue, new \stdClass());
        $mutator->object = (object) ['a' => 'b'];
        $this->assertTrue($mutator->isChanged('object'));
        $this->assertEquals($objectValue, $mutator->getChanges('object'));

        $mutator->object = new \stdClass();
        $this->assertFalse($mutator->isChanged('object'));
    }

    public function testSetInvalid()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Invalid property: invalid'));

        // @phpstan-ignore-next-line
        TestMutator::new()->invalid = 'xx';
    }

    public function testSetService()
    {
        $mutator = TestMutator::new();
        $mutator->cache = wei()->cache;

        $this->assertEquals(wei()->cache, $mutator->cache);
    }

    public function testSetGet()
    {
        $mutator = TestMutator::new();

        // 转换为内部数据
        $mutator->mutator = 'abc';

        // 还原为外部数据
        $this->assertEquals('abc', $mutator->mutator);

        // 转换为别的内外数据
        $mutator->mutator = 'bbc';

        // 还原为别的外部数据
        $this->assertEquals('bbc', $mutator->mutator);
    }

    public function testGetTwice()
    {
        $mutator = TestMutator::new();

        // 转换为内部数据
        $mutator->mutator = 'abc';

        // 还原为外部数据
        $this->assertEquals('abc', $mutator->mutator);

        // 再次获得外部数据
        $this->assertEquals('abc', $mutator->mutator);
    }

    public function testGetterGetTwice()
    {
        $mutator = TestMutator::new();

        $mutator->getter = 'abc';

        // 只有Getter没有Setter,所以返回值是直接对abc做decode
        $this->assertEquals(base64_decode('abc', true), $mutator->getter);

        // 再次调用也是一样的结果
        $this->assertEquals(base64_decode('abc', true), $mutator->getter);
    }

    public function testFind()
    {
        $mutator = TestMutator::find(2);

        $this->assertEquals('mutator', $mutator->mutator);
        $this->assertEquals('getter', $mutator->getter);
        $this->assertEquals(base64_encode('setter'), $mutator->setter);

        $mutator->mutator = 'mutator2';
        $mutator->getter = base64_encode('getter2');
        $mutator->setter = 'setter2';

        $this->assertEquals('mutator2', $mutator->mutator);
        $this->assertEquals('getter2', $mutator->getter);
        $this->assertEquals(base64_encode('setter2'), $mutator->setter);
    }

    public function testNew()
    {
        $mutator = TestMutator::new();

        $this->assertSame('', $mutator->mutator); // 经 getMutatorAttribute 转换
        $this->assertSame('', $mutator->getter); // 经 getGetterAttribute 转换
        $this->assertSame('', $mutator->setter);

        $mutator->mutator = 'mutator2';
        $mutator->getter = base64_encode('getter2');
        $mutator->setter = 'setter2';

        $this->assertEquals('mutator2', $mutator->mutator);
        $this->assertEquals('getter2', $mutator->getter);
        $this->assertEquals(base64_encode('setter2'), $mutator->setter);
    }

    public function testSave()
    {
        $mutator = TestMutator::save([
            'mutator' => 'mutator2',
            'getter' => base64_encode('getter2'),
            'setter' => 'setter2',
        ]);

        $this->assertEquals('mutator2', $mutator->mutator);
        $this->assertEquals('getter2', $mutator->getter);
        $this->assertEquals(base64_encode('setter2'), $mutator->setter);

        $mutator->mutator = 'mutator3';
        $mutator->getter = base64_encode('getter3');
        $mutator->setter = 'setter3';

        $this->assertEquals('mutator3', $mutator->mutator);
        $this->assertEquals('getter3', $mutator->getter);
        $this->assertEquals(base64_encode('setter3'), $mutator->setter);
    }

    public function testFindAll()
    {
        $table = TestMutator::getTable();
        wei()->db->batchInsert($table, [
            [
                'getter' => base64_encode('getter'),
                'setter' => base64_encode('setter'),
                'mutator' => base64_encode('mutator'),
            ],
            [
                'getter' => base64_encode('getter'),
                'setter' => base64_encode('setter'),
                'mutator' => base64_encode('mutator'),
            ],
        ]);

        $mutators = TestMutator::desc('id')->limit(2)->all();

        $this->assertEquals('getter', $mutators[0]->getter);
        $this->assertEquals(base64_encode('setter'), $mutators[0]->setter);
        $this->assertEquals('mutator', $mutators[0]->mutator);

        $this->assertEquals('getter', $mutators[1]->getter);
        $this->assertEquals(base64_encode('setter'), $mutators[1]->setter);
        $this->assertEquals('mutator', $mutators[1]->mutator);
    }

    public function testNewModelCallGetAttribute()
    {
        $mutator = TestMutator::new();
        $this->assertEquals('default value', $mutator->default_value);
    }
}
