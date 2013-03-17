<?php

namespace WidgetTest;

class CacheTestCase extends TestCase
{
    /**
     * @var \Widget\Cache\CacheInterface
     */
    protected $object;

    /**
     * @dataProvider providerForGetterAndSetter
     */
    public function testGetterAndSetter($value, $key)
    {
        $cache = $this->object;
        
        $cache->remove($key);
        $this->assertFalse($cache->get($key));

        $this->assertFalse($cache->replace($key, $value));
        $this->assertTrue($cache->add($key, $value));
        
        $cache->set($key, $value);
        $this->assertEquals($value, $cache->get($key));
        
        $this->assertFalse($cache->add($key, $value));
        $this->assertTrue($cache->replace($key, $value));
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