<?php

namespace WeiTest;

/**
 * @property \Wei\Asset asset
 *
 * @internal
 */
final class AssetTest extends TestCase
{
    /**
     * @dataProvider providerForAsset
     * @param mixed $from
     * @param mixed $to
     * @param mixed $options
     */
    public function testAsset($from, $to, $options = [])
    {
        $asset = new \Wei\Asset([
            'wei' => $this->wei,
        ] + $options);

        $this->assertEquals($asset($from), $to);
    }

    public function providerForAsset()
    {
        return [
            [
                'jquery.js',
                '/jquery.js?v=1',
            ],
            [
                'file.js',
                'js/file.js?v=1',
                [
                    'baseUrl' => 'js/',
                ],
            ],
            [
                'version.js',
                '/version.js?v=1',
                [
                    'version' => '1',
                ],
            ],
            [
                'version.js?a=b',
                '/version.js?a=b&v=1',
                [
                    'version' => '1',
                ],
            ],
        ];
    }

    public function testGetAndSetBaseUrl()
    {
        $this->asset->setBaseUrl('abc');
        $this->assertEquals('abc', $this->asset->getBaseUrl());
    }

    public function testConcat()
    {
        $this->asset->setBaseUrl('abc');
        $this->asset->setOption('concatUrl', '/c/');
        $this->assertEquals('/c/?f=a.js,b/b.js,c/c/c.js&b=abc', $this->asset->concat([
            'a.js', 'b/b.js', 'c/c/c.js',
        ]));
    }
}
