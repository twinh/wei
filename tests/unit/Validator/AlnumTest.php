<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class AlnumTest extends TestCase
{
    /**
     * @dataProvider providerForAlnum
     * @param mixed $input
     */
    public function testAlnum($input)
    {
        $this->assertTrue($this->isAlnum($input));
    }

    /**
     * @dataProvider providerForNotAlnum
     * @param mixed $input
     */
    public function testNotAlnum($input)
    {
        $this->assertFalse($this->isAlnum($input));
    }

    public function providerForAlnum()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['abcedfg'],
            ['a2BcD3eFg4'],
            ['045fewwefds'],
        ];
    }

    public function providerForNotAlnum()
    {
        return [
            ['a bcdefg'],
            ['-213a bcdefg'],
        ];
    }

    /**
     * @dataProvider providerForLocale
     * @param mixed $locale
     * @param mixed $message
     */
    public function testLocale($locale, $message)
    {
        $tran = new \Wei\T([
            'wei' => $this->wei,
            'locale' => $locale,
        ]);

        $validator = new \Wei\Validator\Alnum([
            'wei' => $this->wei,
            't' => $tran,
        ]);

        $validator('1.2');

        $this->assertEquals($message, $validator->getFirstMessage());
    }

    public function providerForLocale()
    {
        return [
            ['en', 'This value must contain letters (a-z) and digits (0-9)'],
            ['zh-CN', '该项只能由字母(a-z)和数字(0-9)组成'],
        ];
    }
}
