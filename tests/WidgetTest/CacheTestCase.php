<?php

namespace WidgetTest;

class CacheTestCase extends TestCase
{
    /**
     * @var \Widget\Cache\CacheInterface
     */
    protected $object;
    
    protected $name;
       
//    public function createCacheWidget()
//    {
//        $cache = $this->widget->newInstance($this->widgetName);
//        
//        return $cache;
//    }
    
    /**
     * @dataProvider providerForGetterAndSetter
     */
    public function testGetterAndSetter($value, $key)
    {
        $cache = $this->object;
        
        $cache->remove($key);
        
        $this->assertFalse($cache->get($key));
        
        $cache->set($key, $value);
        
        $this->assertEquals($value, $this->object->get($key));
    }
    
    public function providerForGetterAndSetter()
    {
        $obj = new \stdClass;
        
        return array(
            array(array(),  'array'),
            array(true,     'bool'),
            array(1.2,      'float'),
            array(1,        'int'),
            array(1,        'integer'),
            array(null,     'null'),
            array('1',      'numeric'),
            array($obj,     'object'),
        );
    }
}