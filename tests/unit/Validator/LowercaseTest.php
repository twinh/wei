<?php

namespace WeiTest\Validator;

class LowercaseTest extends TestCase
{
    /**
     * @dataProvider providerForLowercase
     */
    public function testLowercase($input, $options = null)
    {
        $this->assertTrue($this->isLowercase($input, $options));
    }

    /**
     * @dataProvider providerForNotLowercase
     */
    public function testNotLowercase($input, $options = null)
    {
        $this->assertFalse($this->isLowercase($input, $options));
    }

    public function providerForLowercase()
    {
        return array(
            array('abc'),
            array('abc123'),
            array('abc中文'),
            array('τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός'),
            array('mary had a little lamb and she loved it so')
        );
    }

    public function providerForNotLowercase()
    {
        return array(
            array('ABC'),
            array('Abc'),
            array('Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός')
        );
    }
}
