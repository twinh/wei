<?php

namespace WeiTest\Validator;

class QQTest extends TestCase
{
    /**
     * @dataProvider providerForQQ
     */
    public function testQQ($input)
    {
        $this->assertTrue($this->isQQ($input));
    }

    /**
     * @dataProvider providerForNotQQ
     */
    public function testNotQQ($input)
    {
        $this->assertFalse($this->isQQ($input));
    }

    public function providerForQQ()
    {
        return array(
            array('1234567'),
            array('1234567'),
            array('123456789'),
        );
    }

    public function providerForNotQQ()
    {
        return array(
            array('1000'), // Too short
            array('011111'), // Should not start with zero
            array('134.433'),
            array('not digit'),
        );
    }
}
