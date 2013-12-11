<?php

namespace WeiTest\Validator;

class NullTest extends TestCase
{
    /**
     * @dataProvider providerForNull
     */
    public function testNull($input)
    {
        // Avoid conflict with PHPUnit_Framework_Assert::isNull
        $null = $this->isNull;
        $this->assertTrue($null($input));
    }

    /**
     * @dataProvider providerForNotNull
     */
    public function testNotNull($input)
    {
        $null = $this->isNull;
        $this->assertFalse($null($input));
    }

    public function providerForNull()
    {
        return array(
            array(null),
        );
    }

    public function providerForNotNull()
    {
        return array(
            array(''),
            array(false),
            array(0),
            array(0.0),
            array(array()),
            array('0')
        );
    }
}
