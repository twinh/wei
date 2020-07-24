<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class UppercaseTest extends TestCase
{
    /**
     * @dataProvider providerForUppercase
     * @param mixed $input
     * @param mixed|null $options
     */
    public function testUppercase($input, $options = null)
    {
        $this->assertTrue($this->isUppercase($input, $options));
    }

    /**
     * @dataProvider providerForNotUppercase
     * @param mixed $input
     * @param mixed|null $options
     */
    public function testNotUppercase($input, $options = null)
    {
        $this->assertFalse($this->isUppercase($input, $options));
    }

    public function providerForUppercase()
    {
        return [
            ['ABC'],
            ['ABC123'],
            ['ABC中文'],
            ['ΤΆΧΙΣΤΗ ΑΛΏΠΗΞ ΒΑΦΉΣ ΨΗΜΈΝΗ ΓΗ, ΔΡΑΣΚΕΛΊΖΕΙ ΥΠΈΡ ΝΩΘΡΟΎ ΚΥΝΌΣ'],
            ['PRINTS MARY HAD A LITTLE LAMB AND SHE LOVED IT SO'],
        ];
    }

    public function providerForNotUppercase()
    {
        return [
            ['abc'],
            ['Abc'],
            ['Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός'],
        ];
    }
}
