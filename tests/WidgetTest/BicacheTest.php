<?php

namespace WidgetTest;

class BicacheTest extends CacheTestCase
{
    protected function setUp()
    {
        // FIXME
        if (!extension_loaded('apc') || !ini_get('apc.enable_cli')) {
            $this->markTestSkipped('Extension "apc" is not loaded.');
        }
        parent::setUp();
    }
}