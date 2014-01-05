<?php

namespace WeiTest\Validator;

class ContainsTest extends TestCase
{
    /**
     * @dataProvider providerForContains
     */
    public function testContains($input, $search, $regex = false)
    {
        $this->assertTrue($this->isContains($input, $search, $regex));
    }

    /**
     * @dataProvider providerForNotContains
     */
    public function testNotContains($input, $search, $regex = false)
    {
        $this->assertFalse($this->isContains($input, $search, $regex));
    }

    public function providerForContains()
    {
        return array(
            array(123, 1),
            array('abc', 'a'),
            array('@#$', '@'),
            array('ABC', '/a/i', true),

        );
    }

    public function providerForNotContains()
    {
        return array(
            array('123', '4'),
            array('ABC', '/a/', true)
        );
    }
}
