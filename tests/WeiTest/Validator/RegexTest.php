<?php

namespace WeiTest\Validator;

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
            array('This is Wei Framework.', '/wei/i'),
        );
    }

    public function providerForNotRegex()
    {
        return array(
            array('This is Wei Framework.', '/that/i'),
            array('Abc', '/abc/')
        );
    }
}
