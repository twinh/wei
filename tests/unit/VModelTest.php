<?php

declare(strict_types=1);

namespace WeiTest;

use InvalidArgumentException;
use Wei\Ret;
use Wei\RetTrait;
use Wei\V;
use WeiTest\Model\Fixture\TestV;

/**
 * @internal
 */
final class VModelTest extends TestCase
{
    use RetTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_vs')
            ->id()
            ->bool('bool_column')->comment('Bool column')
            ->tinyInt('tiny_int_column')
            ->uTinyInt('u_tiny_int_column')
            ->smallInt('small_int_column')
            ->uSmallInt('u_small_int_column')
            ->mediumInt('medium_int_column')
            ->uMediumInt('u_medium_int_column')
            ->int('int_column')
            ->uInt('u_int_column')
            ->bigInt('big_int_column')
            ->uBigInt('u_big_int_column')
            ->timestamp('timestamp_column')
            ->datetime('datetime_column')
            ->date('date_column')
            ->decimal('decimal_column', 3, 1)
            ->uDecimal('u_decimal_column', 6, 3)
            ->char('char_column', 3)
            ->string('string_column', 32)
            ->text('text_column')
            ->mediumText('medium_text_column')
            ->longText('long_text_column')
            ->json('json_column')
            ->exec();
    }

    public static function tearDownAfterClass(): void
    {
        TestV::getColumns();
        static::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists(['test_vs']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    public function testGetSetModel()
    {
        $model = TestV::new();

        $v = V::new();
        $v->setModel($model);

        $this->assertSame($model, $v->getModel());
    }

    public function testModelNotSpecified()
    {
        $this->expectExceptionObject(new InvalidArgumentException('$model argument is required'));

        $v = V::new();
        $v->modelColumn('test', 'Test');
    }

    public function testColumnNotFound()
    {
        $this->expectExceptionObject(new InvalidArgumentException(
            'Column "test" not found in model "WeiTest\Model\Fixture\TestV"'
        ));

        $v = V::new();
        $v->modelColumn('test', 'Test', TestV::new());
    }

    public function testUnsupportedType()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Unsupported column type: longText'));
        $v = V::new();
        $v->modelColumn('long_text_column', 'Test', TestV::new());
    }

    public function testKey()
    {
        $v = V::new();
        $v->modelColumn('bool_column', 'Test', TestV::new(), ['test', 'bool']);
        $ret = $v->check([
            'test' => [
                'bool' => true,
            ],
        ]);
        $this->assertRetSuc($ret);
    }

    public function testLabelFromTable()
    {
        $v = V::new();
        $v->setModel(TestV::new());
        $v->modelColumn('bool_column');
        $ret = $v->check([
            'bool_column' => 'abc',
        ]);
        $this->assertRetErr($ret, 'Bool column must be a bool value');
    }

    /**
     * @dataProvider providerForModelColumn
     * @param mixed $name
     * @param mixed $value
     */
    public function testModelColumn($name, $value, Ret $expectedRet)
    {
        $v = V::new();
        $model = TestV::new();
        $v->modelColumn($name, 'The ' . $name, $model);

        $ret = $v->check([
            $name => $value,
        ]);

        if ($expectedRet->isErr()) {
            $this->assertRetErr($ret, $expectedRet->getMessage());
        } else {
            $this->assertRetSuc($expectedRet);
        }
    }

    public function providerForModelColumn(): array
    {
        return [
            [
                'bool_column',
                'a',
                $this->err('The bool_column must be a bool value'),
            ],
            [
                'tiny_int_column',
                2 ** 7,
                $this->err('The tiny_int_column must be less than or equal to 127'),
            ],
            [
                'u_tiny_int_column',
                2 ** 8,
                $this->err('The u_tiny_int_column must be less than or equal to 255'),
            ],
            [
                'small_int_column',
                2 ** 15,
                $this->err('The small_int_column must be less than or equal to 32767'),
            ],
            [
                'u_small_int_column',
                2 ** 16,
                $this->err('The u_small_int_column must be less than or equal to 65535'),
            ],
            [
                'int_column',
                2 ** 31,
                $this->err('The int_column must be less than or equal to 2147483647'),
            ],
            [
                'u_int_column',
                2 ** 32,
                $this->err('The u_int_column must be less than or equal to 4294967295'),
            ],
            [
                'big_int_column',
                '9223372036854775808',
                $this->err('The big_int_column must be less than or equal to 9223372036854775807'),
            ],
            [
                'big_int_column',
                '',
                $this->suc(),
            ],
            [
                'u_big_int_column',
                '18446744073709551616',
                $this->err('The u_big_int_column must be less than or equal to 18446744073709551615'),
            ],
            [
                'timestamp_column',
                '1000-01-01 00:00:00',
                $this->err('The timestamp_column must between 1970-01-01 00:00:01 and 2038-01-19 03:14:07'),
            ],
            [
                'datetime_column',
                '',
                $this->err('The datetime_column must be a valid datetime'),
            ],
            [
                'decimal_column',
                '123.1',
                $this->err('The decimal_column must be less than or equal to 99.9'),
            ],
            [
                'u_decimal_column',
                '1231231.1',
                $this->err('The u_decimal_column must be less than or equal to 999.999'),
            ],
            [
                'char_column',
                '1234',
                $this->err('The char_column must be no more than 3 character(s)'),
            ],
            [
                'string_column',
                str_repeat('1', 32) . '1',
                $this->err('The string_column must be no more than 32 character(s)'),
            ],
            [
                'text_column',
                str_repeat('1', 65535) . '1',
                $this->err('The text_column must have a length lower than 65535'),
            ],
            [
                'medium_text_column',
                str_repeat('1', 16777215 + 1),
                $this->err('The medium_text_column must have a length lower than 16777215'),
            ],
            [
                'json_column',
                'test',
                $this->err('The json_column must be an array or object'),
            ],
        ];
    }

    public function testRequiredIfNew()
    {
        $v = V::defaultOptional();
        $v->setModel(TestV::new());
        $v->modelColumn('string_column', 'String')->requiredIfNew();
        $ret = $v->check([]);
        $this->assertRetErr($ret, 'String is required');
    }

    public function testRequiredIfNewWithExistsModel()
    {
        $v = V::defaultOptional();
        $v->setModel(TestV::save());
        $v->modelColumn('string_column', 'String')->requiredIfNew();
        $ret = $v->check([]);
        $this->assertRetSuc($ret);
    }

    public function testRequiredIfNewWithModelArg()
    {
        $v = V::defaultOptional();
        $v->string('string_column', 'String')->requiredIfNew(TestV::new());
        $ret = $v->check([]);
        $this->assertRetErr($ret, 'String is required');
    }

    public function testRequiredIfNewWithoutModel()
    {
        $this->expectExceptionObject(new InvalidArgumentException('$model argument is required'));

        $v = V::new();
        $v->string('test', 'Test')->requiredIfNew();
    }

    public function testModelNotDup()
    {
        $name = uniqid('Test');
        TestV::save(['string_column' => $name]);

        $v = V::new();
        $v->setModel(TestV::new());
        $v->string('string_column', 'Test')->notModelDup();
        $ret = $v->check([
            'string_column' => $name,
        ]);
        $this->assertRetErr($ret, 'Test already exists');
    }

    public function testModelNotDupWithUpdate()
    {
        $name = uniqid('Test');
        $model = TestV::save(['string_column' => $name]);

        $v = V::new();
        $v->setModel($model);
        $v->string('string_column', 'Test')->notModelDup();
        $ret = $v->check([
            'string_column' => $name,
        ]);
        $this->assertRetSuc($ret);
    }

    public function testModelNotDupWithoutModel()
    {
        $this->expectExceptionObject(new InvalidArgumentException('$model argument is required'));

        $v = V::new();
        $v->string('test', 'Test')->notModelDup();
    }
}
