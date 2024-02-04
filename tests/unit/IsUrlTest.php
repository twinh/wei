<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsUrlTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUrl
     * @param mixed $input
     * @param mixed $options
     */
    public function testUrl($input, $options = [])
    {
        $this->assertTrue($this->isUrl($input, $options));
    }

    /**
     * @dataProvider providerForNotUrl
     * @param mixed $input
     * @param mixed $options
     */
    public function testNotUrl($input, $options = [])
    {
        $this->assertFalse($this->isUrl($input, $options));
    }

    public static function providerForUrl()
    {
        return [
            ['http://www.google.com'],
            ['http://example.com'],
            ['http://exa-mple.com'],
            ['file:///tmp/test.c'],
            ['ftp://ftp.example.com/tmp/'],
            ['http://qwe'],
            ['https://www.example.com/', [
                'path' => true,
            ]],
            ['https://127.0.0.1/', [
                'path' => true,
            ]],
            ['http://www.example.com/index.html?q=123', [
                'query' => true,
            ]],
        ];
    }

    public static function providerForNotUrl()
    {
        return [
            ['http://exa_mple.com'],
            ['g.cn'],
            ['http//www.example'],
            ['http:/www.example'],
            ['/tmp/test.c'],
            ['/'],
            ["http://\r\n/bar"],
            ['https://localhost', [
                'path' => true,
            ]],
            ['https://localhost', [
                'query' => true,
            ]],
        ];
    }
}
