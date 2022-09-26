<?php

namespace WeiTest;

/**
 * @mixin \IsImageUrlMixin
 * @internal
 */
final class IsImageUrlTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUrl
     * @param mixed $input
     */
    public function testImageUrl($input)
    {
        $this->assertTrue($this->isImageUrl($input));
    }

    /**
     * @dataProvider providerForNotUrl
     * @param mixed $input
     */
    public function testNotImageUrl($input)
    {
        $this->assertFalse($this->isImageUrl($input));
    }

    public function providerForUrl()
    {
        return [
            ['https://example.com/dir/1.jpg'],
            ['https://example.com/dir/2.jpeg'],
            ['https://example.com/dir/3.bmp'],
            ['https://example.com/dir/4.gif'],
            ['https://example.com/dir/5.png'],
            ['https://www.google.com/1.jpg'],
            ['http://www.google.com/1.jpg'],
            ['http://www.google.com/1.jpg?a=b'],
            // filter_var consider valid
            ['file:///tmp/test.png'],
            ['abc://example/1.jpg'],
        ];
    }

    public function providerForNotUrl()
    {
        return [
            ['http://exa_mple.com'],
            ['g.cn'],
            ['http//www.example'],
            ['http:/www.example'],
            ['/tmp/test.c'],
            ['/'],
            ["http://\r\n/bar"],
            // filter_var consider invalid
            ['//example/1.jpg'],
            ['https://example.com/dir/' . str_repeat('1', 255) . '.jpg'],
        ];
    }
}
