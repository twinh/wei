<?php

namespace WeiTest\Validator;

use WeiTest\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The name of validator
     *
     * @var string
     */
    protected $name;

    /**
     * The validator options for input test
     *
     * @var array
     */
    protected $inputTestOptions = [];

    protected static $resource;

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        if (static::$resource) {
            fclose(static::$resource);
            static::$resource = null;
        }
    }

    public function providerForInput()
    {
        // Initial test fixtures
        $data = [
            // boolean
            true,
            false,
            // integer
            1234,
            -123,
            0123,
            0x1A,
            // float
            1.234,
            1.2e3,
            7E-10,
            // string
            'this is a simple string',
            // object
            new \stdClass(),
            new \ArrayObject([1, 3]),
            new \DateTime(),
            // resource
            curl_init(),
            // null
            null,
            // callback
            function () {
            },
        ];

        // Convert to test data
        foreach ($data as &$value) {
            $value = [$value];
        }

        return $data;
    }

    /**
     * @dataProvider providerForInput
     * @param mixed $input
     */
    public function testInput($input)
    {
        // Gets validator name WeiTest\Validator\LengthTest => Length
        $name = $this->name ?: substr(static::class, strrpos(static::class, '\\') + 1, -4);
        $validator = $this->validate->createRuleValidator($name, $this->getInputTestOptions());

        // The validator should accept any type of INPUT and do NOT raise any
        // exceptions or errors
        $this->assertIsBool($validator($input));
    }

    public function createResource()
    {
        if (!static::$resource) {
            static::$resource = fopen(__FILE__, 'r');
        }

        return static::$resource;
    }

    protected function getInputTestOptions()
    {
        return $this->inputTestOptions;
    }
}
