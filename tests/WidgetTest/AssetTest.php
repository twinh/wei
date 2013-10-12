<?php

namespace WidgetTest;

class AssetTest extends TestCase
{
    /**
     * @dataProvider providerForAsset
     */
    public function testAsset($from, $to, $options = array())
    {
        $asset = new \Widget\Asset(array(
            'widget' => $this->widget,
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
            )
        );
    }
}