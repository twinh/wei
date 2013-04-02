<?php

namespace WidgetTest;

class DbCacheTest extends CacheTestCase
{
    public function testGetDbh()
    {
        $this->assertInstanceOf('\PDO', $this->dbCache->getDbh());
    }
    
    public function testGetDriver()
    {
        $this->assertInstanceOf('\Widget\Cache\Db\DriverInterface', $this->dbCache->getDriver());
    }
}