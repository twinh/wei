<?php

namespace WeiTest\Validator;

class UppercaseTest extends TestCase
{
    /**
     * @dataProvider providerForUppercase
     */
    public function testUppercase($input, $options = null)
    {
        $this->assertTrue($this->isUppercase($input, $options));
    }

    /**
     * @dataProvider providerForNotUppercase
     */
    public function testNotUppercase($input, $options = null)
    {
        $this->assertFalse($this->isUppercase($input, $options));
    }

    public function providerForUppercase()
    {
        return array(
            array('ABC'),
            array('ABC123'),
            array('ABC中文'),
            array('ΤΆΧΙΣΤΗ ΑΛΏΠΗΞ ΒΑΦΉΣ ΨΗΜΈΝΗ ΓΗ, ΔΡΑΣΚΕΛΊΖΕΙ ΥΠΈΡ ΝΩΘΡΟΎ ΚΥΝΌΣ'),
            array('PRINTS MARY HAD A LITTLE LAMB AND SHE LOVED IT SO')
        );
    }

    public function providerForNotUppercase()
    {
        return array(
            array('abc'),
            array('Abc'),
            array('Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός')
        );
    }
}
