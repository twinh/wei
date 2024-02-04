<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsLowercaseTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLowercase
     * @param mixed $input
     * @param mixed|null $options
     */
    public function testLowercase($input, $options = null)
    {
        $this->assertTrue($this->isLowercase($input, $options));
    }

    /**
     * @dataProvider providerForNotLowercase
     * @param mixed $input
     * @param mixed|null $options
     */
    public function testNotLowercase($input, $options = null)
    {
        $this->assertFalse($this->isLowercase($input, $options));
    }

    public static function providerForLowercase()
    {
        return [
            ['abc'],
            ['abc123'],
            ['abc中文'],
            ['τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός'],
            ['mary had a little lamb and she loved it so'],
        ];
    }

    public static function providerForNotLowercase()
    {
        return [
            ['ABC'],
            ['Abc'],
            ['Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός'],
        ];
    }
}
