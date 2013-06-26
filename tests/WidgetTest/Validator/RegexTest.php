<?php

namespace WidgetTest\Validator;

class RegexTest extends TestCase
{
    protected $inputTestOptions = array(
        'pattern' => '(.+?)'
    );
    
    /**
     * @dataProvider providerForRegex
     */
    public function testRegex($input, $regex)
    {
        $this->assertTrue($this->isRegex($input, $regex));
    }

    /**
     * @dataProvider providerForNotRegex
     */
    public function testNotRegex($input, $regex)
    {
        $this->assertFalse($this->isRegex($input, $regex));
    }

    public function providerForRegex()
    {
        return array(
            array('This is Widget Framework.', '/widget/i'),
        );
    }

    public function providerForNotRegex()
    {
        return array(
            array('This is Widget Framework.', '/that/i'),
            array('Abc', '/abc/')
        );
    }
}
