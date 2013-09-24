<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
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
    protected $inputTestOptions = array();

    protected static $resource;

    public function getInputs()
    {
        return array(
            // boolen
            true, false,
            // interger
            1234, -123, 0123, 0x1A,
            // float
            1.234, 1.2e3, 7E-10,
            // string
            'this is a simple string',
            // object
            new \stdClass, new \ArrayObject(array(1, 3)), new \DateTime(),
            // FIXME
            //new \SplFileInfo(__FILE__),
            // resource
            $this->createResource(),
            // null
            null,
            // callback
            function(){}
        );
    }

    public function testInput()
    {
        // Gets validator name WidgetTest\Validator\LengthTest => Length
        $name = $this->name ?: substr(get_class($this), strrpos(get_class($this), '\\') + 1, -4);
        $validator = $this->validate->createRuleValidator($name, $this->inputTestOptions);

        // The validator should accept any type of INPUT and do NOT raise any
        // exceptions or errors
        foreach ($this->getInputs() as $input) {
            $this->assertInternalType('boolean', $validator($input));
        }
    }

    public function createResource()
    {
        if (!static::$resource) {
            static::$resource = fopen(__FILE__, 'r');
        }

        return static::$resource;
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        if (static::$resource) {
            fclose(static::$resource);
            static::$resource = null;
        }
    }
}