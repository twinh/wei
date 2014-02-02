<?php

namespace WeiTest;

/**
 * @property \Wei\Asset asset
 */
class AssetTest extends TestCase
{
    /**
     * @dataProvider providerForAsset
     */
    public function testAsset($from, $to, $options = array())
    {
        $asset = new \Wei\Asset(array(
            'wei' => $this->wei,
        ) + $options);

        $this->assertEquals($asset($from), $to);
    }

    public function providerForAsset()
    {
        return array(
            array(
                'jquery.js',
                '/jquery.js?v=1',
            ),
            array(
                'file.js',
                'js/file.js?v=1',
                array(
                    'baseUrl' => 'js/'
                )
            ),
            array(
                'version.js',
                '/version.js?v=1',
                array(
                    'version' => '1'
                ),
            ),
            array(
                'version.js?a=b',
                '/version.js?a=b&v=1',
                array(
                    'version' => '1'
                ),
            )
        );
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
        $this->assertEquals('/c/?b=abc&f=a.js,b/b.js,c/c/c.js', $this->asset->concat(array(
            'a.js', 'b/b.js', 'c/c/c.js'
        )));
    }
}
