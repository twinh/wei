<?php

namespace WeiTest;

/**
 * @internal
 * @mixin \StrMixin
 */
final class StrTest extends TestCase
{
    public function providerForSnake()
    {
        return [
            ['a', 'a'],
            ['abc', 'abc'],
            ['abC', 'ab_c'],
            ['abCd', 'ab_cd'],
            ['ab_cd', 'ab_cd'],
        ];
    }

    /**
     * @param string $input
     * @param string $output
     * @dataProvider providerForSnake
     */
    public function testSnake($input, $output)
    {
        $this->assertEquals($output, $this->str->snake($input));
    }

    public function providerForDash()
    {
        return [
            ['a', 'a'],
            ['abc', 'abc'],
            ['abC', 'ab-c'],
            ['abCd', 'ab-cd'],
            ['ab-cd', 'ab-cd'],
        ];
    }

    /**
     * @param string $input
     * @param string $output
     * @dataProvider providerForDash
     */
    public function testDash($input, $output)
    {
        $this->assertEquals($output, $this->str->dash($input));
    }

    public function providerForCamel(): array
    {
        return [
            ['a', 'a'],
            ['abc', 'abc'],
            ['abC', 'abC'],
            ['a-bc', 'aBc'],
            ['a_bc', 'aBc'],
            ['ab-cd_ef', 'abCdEf'],
        ];
    }

    /**
     * @param string $input
     * @param string $output
     * @dataProvider providerForCamel
     */
    public function testCamel(string $input, string $output)
    {
        $this->assertEquals($output, $this->str->camel($input));
    }

    public function providerForSingularize()
    {
        return [
            ['test', 'test'],
            ['tests', 'test'],
            ['queries', 'query'],
            ['news', 'news'],
            ['myLists', 'myList'],
        ];
    }

    /**
     * @param string $input
     * @param string $output
     * @dataProvider providerForSingularize
     */
    public function testSingularize($input, $output)
    {
        $this->assertEquals($output, $this->str->singularize($input));
    }

    public function providerForPluralize()
    {
        return [
            ['test', 'tests'],
            ['query', 'queries'],
            ['news', 'news'],
            ['myList', 'myLists'],
        ];
    }

    /**
     * @param string $input
     * @param string $output
     * @dataProvider providerForPluralize
     */
    public function testPluralize($input, $output)
    {
        $this->assertEquals($output, $this->str->pluralize($input));
    }

    /**
     * @param string $input
     * @param int $length
     * @param string $output
     * @return void
     * @dataProvider providerForCut
     */
    public function testCut(string $input, int $length, string $output): void
    {
        $this->assertSame($output, $this->str->cut($input, $length));
    }

    public function providerForCut(): array
    {
        return [
            ['test', 3, 'tes'],
            ['test', 4, 'test'],
            ['test', 5, 'test'],
            ['我爱你', 2, '我爱'],
            ['我爱你', 3, '我爱你'],
            ['我爱你', 4, '我爱你'],
            ['我爱12', 2, '我爱'],
            ['我爱12', 3, '我爱1'],
            ['我爱12', 4, '我爱12'],
        ];
    }
}
