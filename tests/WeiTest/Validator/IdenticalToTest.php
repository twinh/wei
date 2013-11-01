<?php

namespace WeiTest\Validator;

use MyProject\Proxies\__CG__\stdClass;

class IdenticalToTest extends TestCase
{
    /**
     * @dataProvider providerForIdenticalTo
     */
    public function testEquals($input, $equals)
    {
        $this->assertTrue($this->isIdenticalTo($input, $equals));
    }

    /**
     * @dataProvider providerForNotIdenticalTo
     */
    public function testNotEquals($input, $equals)
    {
        $this->assertFalse($this->isIdenticalTo($input, $equals));
    }

    public function providerForIdenticalTo()
    {
        $a = $b = new \stdClass();
        return array(
            array('abc', 'abc'),
            array($a, $b),
            array(1+1, 2),
            array(array(), array()),
            array(0, 0),
            array(false, false)
        );
    }

    public function providerForNotIdenticalTo()
    {
        $a = new \stdClass();
        $b = new \stdClass();
        return array(
            array('abc', 'bbc'),
            array($a, $b),
            array(0, false),
            array(0, null),
            array(0, ''),
            array(0, 0.0),
            array(false, null),
            array(false, ''),
            array(false, 0.0),
            array(null, ''),
            array(null, 0.0),
        );
    }
}
