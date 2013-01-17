<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class TypeTest extends TestCase
{
    protected static $resource;

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
        }
    }

    /**
     * @dataProvider providerForType
     */
    public function testType($input, $type)
    {
        $this->assertTrue($this->isType->__invoke($input, $type));
    }

    /**
     * @dataProvider providerForNotType
     */
    public function testNotType($input, $type)
    {
        $this->assertFalse($this->isType->__invoke($input, $type));
    }

    /**
     * @expectedException \Widget\Exception
     */
    public function testExcptionWithUnkonwnType()
    {
        $this->isType->__invoke('xx', 'unkonwn type');
    }

    public function providerForType()
    {
        $obj = new \stdClass;
        $res = $this->createResource();

        return array(
            // is_xxx
            array(array(),  'array'),
            array(true,     'bool'),
            array(1.2,      'float'),
            array(1,        'int'),
            array(1,        'integer'),
            array(null,     'null'),
            array('1',      'numeric'),
            array($obj,     'object'),
            array($res,     'resource'),
            array(1,        'scalar'),
            array(1.1,      'scalar'),
            array('str',    'scalar'),
            array(true,     'scalar'),
            array('str',    'string'),
            // ctype_xxx
            array('abc',    'alnum'),
            array('a2B',    'alnum'),
            array('123',    'alnum'),
            array('dXy',    'alpha'),
            array('abc',    'alpha'),
            array("\n\r\t", 'cntrl'),
            array('10002',  'digit'),
            array('arf12',  'graph'),
            array('qiutoa', 'lower'),
            array("A#@%",   'print'),
            array('*&$()',  'punct'),
            array("\n\r\t", 'space'),
            array('LMNSDO', 'upper'),
            array('10BC9',  'xdigit'),
        );
    }

    public function providerForNotType()
    {
        $obj = new \stdClass;
        $res = $this->createResource();

        return array(
            // is_xxx
            array('1',      'array'),
            array('true',   'bool'),
            array('1.2',    'float'),
            array('1',      'int'),
            array('1',      'integer'),
            array('null',   'null'),
            array('1-2',    'numeric'),
            array('1',      'object'),
            array(1,        'resource'),
            array(array(),  'scalar'),
            array($obj,     'scalar'),
            array($res,     'scalar'),
            // ctype_xxx
            array('a bc',   'alnum'),
            array('-213a',  'alnum'),
            array('123',    'alpha'),
            array('a bc',   'alpha'),
            array('arf12',  'cntrl'),
            array('182.20', 'digit'),
            array('wsl!12', 'digit'),
            array("\n\r\t", 'graph'),
            array('aac123', 'lower'),
            array('QASsdk', 'lower'),
            array("sdf\n",  'print'),
            array('!@ # $', 'punct'),
            array("\narf1", 'space'),
            array('LWC13',  'upper'),
            array('AR1012', 'xdigit')
        );
    }
}
