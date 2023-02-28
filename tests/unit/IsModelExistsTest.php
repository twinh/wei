<?php

namespace WeiTest;

use WeiTest\Fixtures\DbTrait;
use WeiTest\Model\Fixture\TestUser;

/**
 * @mixin \IsModelExistsMixin
 */
final class IsModelExistsTest extends BaseValidatorTestCase
{
    use DbTrait;

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::dropTables();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->initFixtures();
    }

    protected $inputTestOptions = [
        'model' => TestUser::class,
    ];

    public function testModelClass()
    {
        $result = $this->isModelExists(1, TestUser::class);
        $this->assertTrue($result);
    }

    public function testModelInstance()
    {
        $result = $this->isModelExists(1, TestUser::where('group_id', 1));
        $this->assertTrue($result);
    }

    public function testModelInvalidString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Expected "model" option to be an existing model class or instance of Model, "xx" given'
        );
        $this->isModelExists(1, 'xx');
    }

    public function testModelInvalidClass()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Expected "model" option to be an existing model class or instance of Model, "' . __CLASS__ . '" given'
        );
        $this->isModelExists(1, __CLASS__);
    }

    public function testNotModelExists()
    {
        $result = $this->isModelExists(99, TestUser::class);
        $this->assertFalse($result);
    }
}
