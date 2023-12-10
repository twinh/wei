<?php

declare(strict_types=1);

namespace WeiTest\Model;

use Wei\Req;
use WeiTest\Model\Fixture\TestCamelCase;
use WeiTest\TestCase;

/**
 * @internal
 */
final class CamelCaseTraitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::dropTables();

        wei()->schema->table('test_camel_cases')
            ->id()
            ->int('test_user_id')
            ->exec();
    }

    public static function tearDownAfterClass(): void
    {
        self::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_camel_cases');
    }

    public function testFromArray()
    {
        $camelCase = TestCamelCase::new();

        $camelCase->fromArray([
            'testUserId' => 1,
        ]);

        $this->assertEquals(1, $camelCase['testUserId']);
    }

    public function testFromArrayReqIgnoreSnake()
    {
        $camelCase = TestCamelCase::new();

        /** @var Req $request */
        $request = wei()->newInstance('request');
        $request->fromArray([
            'test_user_id' => 1,
        ]);
        $camelCase->fromArray($request);

        $this->assertSame(0, $camelCase['testUserId']);

        $request->fromArray([
            'test_user_id' => 1,
            'testUserId' => 2,
        ]);
        $camelCase->fromArray($request);

        $this->assertEquals(2, $camelCase['testUserId']);
    }

    public function testFromArrayArrayIgnoreSnake()
    {
        $camelCase = TestCamelCase::new();

        $camelCase->fromArray([
            'test_user_id' => 1,
        ]);

        $this->assertEquals(0, $camelCase['testUserId']);

        $camelCase->fromArray([
            'test_user_id' => 1,
            'testUserId' => 2,
        ]);

        $this->assertEquals(2, $camelCase['testUserId']);
    }

    public function testToArray()
    {
        $camelCase = TestCamelCase::new();

        $camelCase->fromArray([
            'testUserId' => 1,
        ]);

        $this->assertEquals([
            'testUserId' => 1,
            'id' => null,
        ], $camelCase->toArray());
    }

    public function testGetSet()
    {
        $camelCase = TestCamelCase::new();

        $this->assertSame(0, $camelCase['testUserId']);

        $camelCase['testUserId'] = 1;

        $this->assertEquals(1, $camelCase['testUserId']);
    }

    public function testNoExtraKey()
    {
        $camelCase = TestCamelCase::new();

        $camelCase['testUserId'] = 2;
        $this->assertEquals(2, $camelCase['testUserId']);

        $data = $camelCase->toArray();
        $this->assertArrayHasKey('testUserId', $data);
        $this->assertArrayNotHasKey('test_user_id', $data);
    }
}
