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
                '/assets/jquery.js',
            ),
            array(
                'file.js',
                '/js/file.js',
                array(
                    'dir' => 'js'
                )
            ),
            array(
                'version.js',
                '/assets/version.js?v=1',
                array(
                    'version' => '1'
                ),
            )
        );
    }
}