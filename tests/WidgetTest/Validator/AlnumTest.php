<?php

namespace WidgetTest\Validator;

class AlnumTest extends TestCase
{
    /**
     * @dataProvider providerForAlnum
     */
    public function testAlnum($input)
    {
        $this->assertTrue($this->isAlnum($input));
    }

    /**
     * @dataProvider providerForNotAlnum
     */
    public function testNotAlnum($input)
    {
        $this->assertFalse($this->isAlnum($input));
    }

    public function providerForAlnum()
    {
        return array(
            array('abcedfg'),
            array('a2BcD3eFg4'),
            array('045fewwefds'),
        );
    }

    public function providerForNotAlnum()
    {
        return array(
            array('a bcdefg'),
            array('-213a bcdefg'),
        );
    }
}
