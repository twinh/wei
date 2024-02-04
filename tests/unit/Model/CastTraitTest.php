<?php

declare(strict_types=1);

namespace WeiTest\Model;

use Wei\QueryBuilder;
use WeiTest\Model\Fixture\TestCast;
use WeiTest\Model\Fixture\TestCastObject;
use WeiTest\TestCase;

/**
 * @internal
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
final class CastTraitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::dropTables();

        wei()->schema->table('test_casts')
            ->id('int_column')
            ->int('nullable_int_column')->nullable()
            ->int('nullable_default_int_column')->nullable()->defaults(7)
            ->bigInt('big_int_column')
            ->bigInt('nullable_big_int_column')->nullable()
            ->bool('bool_column')
            ->bool('nullable_bool_column')->nullable()
            ->string('string_column')
            ->string('nullable_string_column')->nullable()
            ->datetime('datetime_column')->nullable(false)
            ->datetime('nullable_datetime_column')->nullable()
            ->date('date_column')->nullable(false)
            ->date('nullable_date_column')->nullable()
            ->string('json_column')
            ->string('nullable_json_column')->nullable()
            ->string('object_column')
            ->string('nullable_object_column')->nullable()
            ->string('default_object_column')->defaults('{"a":"c"}')
            ->string('list_column')
            ->string('nullable_list_column')->nullable()
            ->string('list2_column')
            ->decimal('decimal_column')
            ->decimal('nullable_decimal_column')->nullable()
            ->varBinary('ip_column', 16)
            ->exec();

        wei()->db->batchInsert('test_casts', [
            [
                'int_column' => 1,
                'bool_column' => false,
                'string_column' => '1',
                'datetime_column' => '2018-01-01 00:00:00',
                'date_column' => '2018-01-01',
                'json_column' => '{"a":"b\\\\c","d":"中文"}',
                'object_column' => '{"a":"b"}',
                'list_column' => 'a,b,c',
                'list2_column' => '1|2|3',
            ],
        ]);

        wei()->schema->table('test_cast_objects')
            ->id()
            ->string('object_column')
            ->exec();
    }

    public static function tearDownAfterClass(): void
    {
        self::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists(['test_casts', 'test_cast_objects']);
    }

    public static function providerForSet()
    {
        return [
            [
                [
                    'int_column' => 2,
                    'bool_column' => true,
                    'string_column' => 'string',
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['a' => 'b\c', 'd' => '中文'],
                    'list_column' => ['a', 'b'],
                    'list2_column' => [1, 2],
                    'ip_column' => '1.1.1.1',
                ],
                [
                    'int_column' => 2,
                    'bool_column' => true,
                    'string_column' => 'string',
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['a' => 'b\c', 'd' => '中文'],
                    'list2_column' => [1, 2],
                    'ip_column' => '1.1.1.1',
                ],
            ],
            [
                [
                    'int_column' => '3',
                    'bool_column' => '0',
                    'string_column' => 1,
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['a' => 'b\c', 'd' => '中文'],
                    'list2_column' => [1, 1],
                    'ip_column' => '1',
                ],
                [
                    'int_column' => 3,
                    'bool_column' => false,
                    'string_column' => '1',
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['a' => 'b\c', 'd' => '中文'],
                    'list2_column' => [1, 1],
                    'ip_column' => '',
                ],
            ],
            [
                [
                    'int_column' => '4.1',
                    'bool_column' => 'bool',
                    'string_column' => true,
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => 'abc',
                    'list_column' => 'abc',
                    'list2_column' => '123',
                ],
                [
                    'int_column' => 4,
                    'bool_column' => true,
                    'string_column' => '1',
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['abc'],
                    'list_column' => ['abc'],
                    'list2_column' => [123],
                ],
            ],
        ];
    }

    /**
     * 测试Set后的结果
     *
     * @param array $from
     * @param array $result
     * @dataProvider providerForSet
     */
    public function testSetAsDbType($from, $result)
    {
        $record = TestCast::new();

        $record->fromArray($from);

        // data中的数据不变
        $data = $record->getAttributes();
        foreach ($from as $key => $value) {
            $this->assertSame($value, $data[$key]);
        }

        // 重新加载,数据会改变
        $record->save();
        $record = TestCast::find((int) $record->int_column);
        foreach ($result as $key => $value) {
            $this->assertSame($value, $record->{$key});
        }
    }

    public static function providerForGetAsPhpType()
    {
        return [
            [
                [
                    'int_column' => '1',
                    'bool_column' => '0',
                    'string_column' => 1,
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['a' => 'b\c', 'd' => '中文'],
                    'list_column' => ['a', 'b'],
                    'list2_column' => [1, 2],
                    'ip_column' => '1.1.1.1',
                ],
                [
                    'int_column' => 1,
                    'bool_column' => false,
                    'string_column' => '1',
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => ['a' => 'b\c', 'd' => '中文'],
                    'list_column' => ['a', 'b'],
                    'list2_column' => [1, 2],
                    'ip_column' => '1.1.1.1',
                ],
            ],
            [
                [
                    'int_column' => 'abc',
                    'bool_column' => '2',
                    'string_column' => 1,
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => '{"a":"b\\c","d":"中文"}',
                    'list_column' => 'a|b',
                    'list2_column' => '1,2',
                    'ip_column' => '1',
                ],
                [
                    'int_column' => 0,
                    'bool_column' => true,
                    'string_column' => '1',
                    'datetime_column' => '2018-01-01 00:00:00',
                    'date_column' => '2018-01-01',
                    'json_column' => [0 => '{"a":"b\c","d":"中文"}'],
                    'list_column' => ['a|b'],
                    'list2_column' => [1],
                    'ip_column' => '',
                ],
            ],
            [
                [
                    'list_column' => 'a,b',
                    'list2_column' => '1|2',
                ],
                [
                    'list_column' => ['a', 'b'],
                    'list2_column' => [1, 2],
                ],
            ],
        ];
    }

    /**
     * 测试Get后的结果
     *
     * @param array $from
     * @param array $result
     * @dataProvider providerForGetAsPhpType
     */
    public function testGetAsPhpType($from, $result)
    {
        $record = TestCast::new();

        $record->fromArray($from);

        foreach ($result as $key => $value) {
            $this->assertSame($value, $record->{$key});
        }
    }

    public function testFind()
    {
        $record = TestCast::find(1);

        $this->assertSame(1, $record->int_column);
        $this->assertFalse($record->bool_column);
        $this->assertSame('1', $record->string_column);
        $this->assertSame('2018-01-01 00:00:00', $record->datetime_column);
        $this->assertSame('2018-01-01', $record->date_column);
        $this->assertSame(['a' => 'b\c', 'd' => '中文'], $record->json_column);
        $this->assertEquals((object) ['a' => 'b'], $record->object_column);
        $this->assertSame(['a', 'b', 'c'], $record->list_column);
        $this->assertSame([1, 2, 3], $record->list2_column);
    }

    public function testSave()
    {
        TestCast::save([
            'int_column' => '5',
            'bool_column' => '0',
            'string_column' => 1,
            'datetime_column' => '2018-01-01 00:00:00',
            'date_column' => '2018-01-01',
            'object_column' => (object) ['a' => 'b'],
            'json_column' => ['a' => 'b\c', 'd' => '中文'],
            'list_column' => ['a', 'b', 'c'],
            'list2_column' => [1, 2, 3],
            'ip_column' => '1.1.1.1',
        ]);

        $data = wei()->db->select('test_casts', ['int_column' => 5]);

        $this->assertSame('5', $data['int_column']);
        $this->assertSame('0', $data['bool_column']);
        $this->assertSame('1', $data['string_column']);
        $this->assertSame('2018-01-01 00:00:00', $data['datetime_column']);
        $this->assertSame('2018-01-01', $data['date_column']);
        $this->assertSame('{"a":"b\\\\c","d":"中文"}', $data['json_column']);
        $this->assertSame('{"a":"b"}', $data['object_column']);
        $this->assertSame('a,b,c', $data['list_column']);
        $this->assertSame('1|2|3', $data['list2_column']);
        $this->assertSame("\x01\x01\x01\x01", $data['ip_column']);
    }

    public function testGetNewModel()
    {
        $cast = TestCast::new();

        $this->assertSame([], $cast->json_column);
        $this->assertInstanceOf(\stdClass::class, $cast->object_column);
        $this->assertSame([], (array) $cast->object_column);
    }

    public function testObjectDefaultValue()
    {
        $cast = TestCast::new();
        $cast2 = TestCast::new();

        $this->assertEquals($cast->object_column, $cast2->object_column);
        $this->assertNotSame($cast->object_column, $cast2->object_column);
    }

    public function testObjectSaveBeDefaultValue()
    {
        $cast = TestCast::save();

        $cast = TestCast::fetch('int_column', $cast->int_column);

        $this->assertSame('{"a":"c"}', $cast['default_object_column']);
    }

    public function testObjectTouchSaveBeDefaultValue()
    {
        $cast = TestCast::new();

        // convert default db value to PHP value
        $default = $cast->default_object_column;
        $this->assertEquals((object) ['a' => 'c'], $default);

        // convert PHP value to db value
        $cast->save();

        $cast = TestCast::fetch('int_column', $cast->int_column);

        $this->assertSame('{"a":"c"}', $cast['default_object_column']);
    }

    public function testObjectSetValue()
    {
        $cast = TestCast::new();
        $cast->object_column->test = 'value';
        $this->assertSame('value', $cast->object_column->test);
    }

    public static function providerForTestStringAsObject(): array
    {
        return [
            [
                [],
                '{}',
            ],
            [
                null,
                '',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestStringAsObject
     * @param mixed $default
     * @param mixed $dbValue
     */
    public function testStringAsObject($default, $dbValue)
    {
        $object = TestCastObject::new(
            [],
            [
                'columns' => [
                    'object_column' => [
                        'cast' => 'object',
                        'default' => $default,
                    ],
                ],
            ]
        );
        $object->save();
        $data = wei()->db->select('test_cast_objects', $object->id);
        $this->assertSame($dbValue, $data['object_column']);
    }

    public function testIncr()
    {
        $cast = TestCast::save([
            'string_column' => 6,
        ]);

        $cast->incr('string_column')->save();

        $cast->reload();

        $this->assertEquals(7, $cast->string_column);
    }

    public function testReloadJson()
    {
        $cast = TestCast::save([
            'json_column' => [
                'a' => 'b',
            ],
        ]);
        $this->assertEquals(['a' => 'b'], $cast->json_column);

        $cast->reload();
        $this->assertEquals(['a' => 'b'], $cast->json_column);
    }

    public function testSetJsonNotArrayValue()
    {
        $cast = TestCast::save([
            'json_column' => null,
        ]);
        $this->assertEquals([], $cast->json_column);

        $data = $this->wei->db->select($cast->getTable(), ['int_column' => $cast->int_column]);
        $this->assertSame('[]', $data['json_column']);

        $cast->reload();
        $this->assertEquals([], $cast->json_column);
    }

    /**
     * @dataProvider providerForSetObjectNotObjectValue
     * @param mixed $input
     * @param mixed $dbValue
     * @param mixed $phpValue
     */
    public function testSetObjectNotObjectValue($input, $dbValue, $phpValue)
    {
        $cast = TestCast::save([
            'object_column' => $input,
        ]);

        $data = $this->wei->db->select($cast->getTable(), ['int_column' => $cast->int_column]);
        $this->assertSame($dbValue, $data['object_column']);

        $this->assertEquals($phpValue, $cast->object_column);

        $cast->reload();
        $this->assertEquals($phpValue, $cast->object_column);
    }

    public static function providerForSetObjectNotObjectValue(): array
    {
        return [
            [
                null,
                '{}',
                new \stdClass(),
            ],
            [
                [],
                '{}',
                new \stdClass(),
            ],
            [
                ['t' => 1],
                '{"t":1}',
                (object) ['t' => 1],
            ],
            [
                ['t' => 1, 't2' => ['t3' => 1]],
                '{"t":1,"t2":{"t3":1}}',
                (object) ['t' => 1, 't2' => (object) ['t3' => 1]],
            ],
            [
                new \ArrayObject(['a' => 'b'], \ArrayObject::ARRAY_AS_PROPS),
                '{"a":"b"}',
                (object) ['a' => 'b'],
            ],
            [
                '1',
                '{"scalar":1}',
                (object) '1',
            ],
            [
                1,
                '{"scalar":1}',
                (object) 1,
            ],
            [
                '"1"',
                '{"scalar":"1"}',
                (object) '1',
            ],
            [
                '{"a":"d"}',
                '{"a":"d"}',
                (object) ['a' => 'd'],
            ],
            [
                '{"a":"}', // invalid
                '{}',
                (object) [],
            ],
        ];
    }

    public function testBeforeSave()
    {
        $cast = TestCast::new();

        $cast::onModelEvent('beforeSave', static function () use ($cast) {
            // @phpstan-ignore-next-line cast to string
            $cast->string_column = count($cast->json_column);
        });

        $cast->save([
            'json_column' => [
                '1',
                '2',
                '3',
            ],
        ]);

        $this->assertSame('3', $cast->string_column);
    }

    public function testConvertEmptyDateStringToNull()
    {
        $cast = TestCast::save([
            'date_column' => '',
            'datetime_column' => '',
        ]);
        $this->assertNull($cast->date_column);
        $this->assertNull($cast->datetime_column);
    }

    public function testSaveNullDate()
    {
        $cast = TestCast::save([
            'datetime_column' => null,
        ]);
        $this->assertNull($cast->datetime_column);
    }

    public function testSetNull()
    {
        $cast = TestCast::new();
        foreach ($cast->getColumns() as $name => $column) {
            $cast->set($name, null);
        }
        $cast->save();

        $data = $this->wei->db->select($cast->getTable(), ['int_column' => $cast->int_column]);
        $this->assertSame([
            'int_column' => (string) $cast->int_column,
            'nullable_int_column' => null,
            'nullable_default_int_column' => null,
            'big_int_column' => '0',
            'nullable_big_int_column' => null,
            'bool_column' => '0',
            'nullable_bool_column' => null,
            'string_column' => '',
            'nullable_string_column' => null,
            'datetime_column' => null,
            'nullable_datetime_column' => null,
            'date_column' => null,
            'nullable_date_column' => null,
            'json_column' => '[]',
            'nullable_json_column' => null,
            'object_column' => '{}',
            'nullable_object_column' => null,
            'default_object_column' => '{}',
            'list_column' => '',
            'nullable_list_column' => null,
            'list2_column' => '',
            'decimal_column' => '0.00',
            'nullable_decimal_column' => null,
            'ip_column' => '',
        ], $data);
    }

    public function testSetDateNull()
    {
        $cast = TestCast::new();
        $cast->date_column = null;
        $this->assertNull($cast->date_column);
    }

    public function testSetListEmptyValue()
    {
        $cast = TestCast::new();

        // @phpstan-ignore-next-line cast to []
        $cast->list_column = '';
        $this->assertSame([], $cast->list_column);

        // @phpstan-ignore-next-line cast to []
        $cast->list_column = null;
        // @phpstan-ignore-next-line
        $this->assertSame([], $cast->list_column);

        $cast->list_column = [];
        $this->assertSame([], $cast->list_column);
    }

    public function testGetColumnCasts()
    {
        $casts = TestCast::new()->getColumnCasts();

        $this->assertSame([
            'int_column' => 'int',
            'nullable_int_column' => 'int',
            'nullable_default_int_column' => 'int',
            'big_int_column' => 'intString',
            'nullable_big_int_column' => 'intString',
            'bool_column' => 'bool',
            'nullable_bool_column' => 'bool',
            'string_column' => 'string',
            'nullable_string_column' => 'string',
            'datetime_column' => 'datetime',
            'nullable_datetime_column' => 'datetime',
            'date_column' => 'date',
            'nullable_date_column' => 'date',
            'json_column' => 'array',
            'nullable_json_column' => 'string',
            'object_column' => 'object',
            'nullable_object_column' => 'object',
            'default_object_column' => 'object',
            'list_column' => 'list',
            'nullable_list_column' => 'string',
            'list2_column' => [
                'list',
                'type' => 'int',
                'separator' => '|',
            ],
            'decimal_column' => 'decimal',
            'nullable_decimal_column' => 'decimal',
            'ip_column' => 'ip',
        ], $casts);
    }

    /**
     * @group change
     */
    public function testUpdate()
    {
        $cast = TestCast::save();

        $cast->json_column = ['a' => 'b'];
        $cast->save();

        // Save string to database
        $data = wei()->db->select($cast->getTable(), ['int_column' => $cast->int_column]);
        $this->assertSame('{"a":"b"}', $data['json_column']);

        // After save, convert back to array
        $this->assertSame(['a' => 'b'], $cast->json_column);
    }

    public function testDefaultToArray()
    {
        $array = TestCast::toArray();

        $this->assertInstanceOf(\stdClass::class, $array['object_column']);

        $this->assertEquals((object) ['a' => 'c'], $array['default_object_column']);

        $this->assertSame([
            'int_column' => null,
            'nullable_int_column' => null,
            'nullable_default_int_column' => 7,
            'big_int_column' => '',
            'nullable_big_int_column' => null,
            'bool_column' => false,
            'nullable_bool_column' => null,
            'string_column' => '',
            'nullable_string_column' => null,
            'datetime_column' => null,
            'nullable_datetime_column' => null,
            'date_column' => null,
            'nullable_date_column' => null,
            'json_column' => [],
            'nullable_json_column' => null,
            'object_column' => $array['object_column'],
            'nullable_object_column' => null,
            'default_object_column' => $array['default_object_column'],
            'list_column' => [],
            'nullable_list_column' => null,
            'list2_column' => [],
            'decimal_column' => '0',
            'nullable_decimal_column' => null,
            'ip_column' => '',
        ], $array);
    }

    public function testDefaultSave()
    {
        $cast = TestCast::save();

        $data = QueryBuilder::table($cast->getTable())->where('int_column', $cast->int_column)->first();

        $this->assertSame('7', $data['nullable_default_int_column']);
        $this->assertSame('[]', $data['json_column']);
    }

    public function testDefaultGet()
    {
        $cast = TestCast::new();
        $this->assertSame(7, $cast->nullable_default_int_column);
    }

    public function testDefaultSet()
    {
        $cast = TestCast::new();

        // @phpstan-ignore-next-line cast to int
        $cast->nullable_default_int_column = '7';
        $this->assertSame(7, $cast->nullable_default_int_column);
    }

    public function testDefaultOverwriteByNew()
    {
        $cast = TestCast::new();
        $this->assertSame(7, $cast->nullable_default_int_column);

        $cast = TestCast::new([
            'nullable_default_int_column' => 1,
        ]);
        $this->assertSame(1, $cast->nullable_default_int_column);
    }

    public function testDefaultUnset()
    {
        $cast = TestCast::new();

        $cast->nullable_int_column = 1;
        $cast->nullable_int_column = null;
        $this->assertNull($cast->nullable_int_column);

        $cast->nullable_default_int_column = 1;
        $cast->nullable_default_int_column = null;
        $this->assertNull($cast->nullable_default_int_column);
    }

    public function testDefaultSetNull()
    {
        $cast = TestCast::new();

        $cast->nullable_int_column = null;
        $this->assertNull($cast->nullable_int_column);

        $cast->nullable_default_int_column = null;
        $this->assertNull($cast->nullable_default_int_column);
    }

    public function testDefaultIsset()
    {
        $cast = TestCast::new();

        $this->assertFalse(isset($cast->nullable_int_column));
        $this->assertTrue(isset($cast->nullable_default_int_column));
    }

    public function testSaveRawObject()
    {
        $cast = TestCast::save([
            'object_column' => (object) 'test',
        ]);
        $this->assertEquals((object) 'test', $cast->object_column);

        $cast->reload();
        $this->assertEquals((object) 'test', $cast->object_column);
    }

    public function testIntString()
    {
        $cast = TestCast::save([
            'big_int_column' => '',
            'nullable_big_int_column' => '1abc',
        ]);
        $this->assertSame('', $cast->big_int_column);
        $this->assertSame('1', $cast->nullable_big_int_column);
    }

    /**
     * @dataProvider providerForTestSaveDecimal
     * @param mixed $value
     * @param mixed $decimalValue
     * @param mixed $nullableDecimalValue
     */
    public function testSaveDecimal($value, $decimalValue, $nullableDecimalValue)
    {
        $cast = TestCast::save([
            'decimal_column' => $value,
            'nullable_decimal_column' => $value,
        ]);
        $this->assertSame($decimalValue, $cast->decimal_column);
        $this->assertSame($nullableDecimalValue, $cast->nullable_decimal_column);
    }

    public static function providerForTestSaveDecimal(): array
    {
        return [
            ['', '0', '0'],
            [null, '0', null],
            [false, '0', '0'],
        ];
    }

    public function testGetColumns()
    {
        $columns = TestCast::getColumns();
        $this->assertSame([
            'int_column' => [
                'type' => 'int',
                'cast' => 'int',
                'unsigned' => true,
                'nullable' => true,
            ],
            'nullable_int_column' => [
                'type' => 'int',
                'cast' => 'int',
                'unsigned' => false,
                'nullable' => true,
            ],
            'nullable_default_int_column' => [
                'type' => 'int',
                'cast' => 'int',
                'unsigned' => false,
                'nullable' => true,
                'default' => 7,
            ],
            'big_int_column' => [
                'type' => 'bigInt',
                'cast' => 'intString',
                'unsigned' => false,
            ],
            'nullable_big_int_column' => [
                'type' => 'bigInt',
                'cast' => 'intString',
                'unsigned' => false,
                'nullable' => true,
            ],
            'bool_column' => [
                'type' => 'bool',
                'cast' => 'bool',
            ],
            'nullable_bool_column' => [
                'type' => 'bool',
                'cast' => 'bool',
                'nullable' => true,
            ],
            'string_column' => [
                'type' => 'string',
                'cast' => 'string',
                'length' => 255,
            ],
            'nullable_string_column' => [
                'type' => 'string',
                'cast' => 'string',
                'length' => 255,
                'nullable' => true,
            ],
            'datetime_column' => [
                'type' => 'datetime',
                'cast' => 'datetime',
                'nullable' => true,
            ],
            'nullable_datetime_column' => [
                'type' => 'datetime',
                'cast' => 'datetime',
                'nullable' => true,
            ],
            'date_column' => [
                'type' => 'date',
                'cast' => 'date',
                'nullable' => true,
            ],
            'nullable_date_column' => [
                'type' => 'date',
                'cast' => 'date',
                'nullable' => true,
            ],
            'json_column' => [
                'type' => 'string',
                'cast' => 'array',
                'length' => 255,
                'default' => [
                ],
            ],
            'nullable_json_column' => [
                'type' => 'string',
                'cast' => 'string',
                'length' => 255,
                'nullable' => true,
            ],
            'object_column' => [
                'type' => 'string',
                'cast' => 'object',
                'length' => 255,
            ],
            'nullable_object_column' => [
                'type' => 'string',
                'cast' => 'object',
                'length' => 255,
                'nullable' => true,
            ],
            'default_object_column' => [
                'type' => 'string',
                'cast' => 'object',
                'length' => 255,
                'default' => '{"a":"c"}',
            ],
            'list_column' => [
                'type' => 'string',
                'cast' => 'list',
                'length' => 255,
                'default' => [
                ],
            ],
            'nullable_list_column' => [
                'type' => 'string',
                'cast' => 'string',
                'length' => 255,
                'nullable' => true,
            ],
            'list2_column' => [
                'type' => 'string',
                'cast' => [
                    0 => 'list',
                    'type' => 'int',
                    'separator' => '|',
                ],
                'length' => 255,
                'default' => [],
            ],
            'decimal_column' => [
                'type' => 'decimal',
                'cast' => 'decimal',
                'unsigned' => false,
                'length' => 10,
                'scale' => 2,
            ],
            'nullable_decimal_column' => [
                'type' => 'decimal',
                'cast' => 'decimal',
                'unsigned' => false,
                'length' => 10,
                'scale' => 2,
                'nullable' => true,
            ],
            'ip_column' => [
                'type' => 'string',
                'cast' => 'ip',
            ],
        ], $columns);
    }
}
