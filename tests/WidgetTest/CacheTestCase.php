<?php

namespace WidgetTest;

class CacheTestCase extends TestCase
{
    /**
     * @var \Widget\Stdlib\AbstractCache
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
        // TODO mysql return false when value column value not changed
        $this->assertTrue($cache->replace($key, uniqid()));
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

    public function testIncrementAndDecrement()
    {
        $cache = $this->object;

        $key = __METHOD__;
        $cache->remove($key);

        // Increase from not exists key
        $this->assertSame(2, $cache->increment($key, 2));

        // Increase from exists key and the offset is 3
        $result = $cache->increment($key, 3);

        echo gettype($result) . "\n";
        var_dump($result);
        $this->assertSame(5, $result);

        $this->assertSame(3, $cache->decrement($key, 2));

        // Negative number
        $this->assertSame(1, $cache->increment($key, -2));

        $this->assertSame(3, $cache->decrement($key, -2));
    }

    public function testClear()
    {
        $cache = $this->object;
        $key = __METHOD__;

        $cache->set($key, $key);
        $this->assertEquals($key, $cache->get($key));

        $cache->clear();
        $this->assertFalse($cache->get($key));
    }

    public function testInvoker()
    {
        $cache = $this->object;
        $key = __METHOD__;

        $cache($key, 'value');

        $this->assertEquals('value', $cache($key));
    }

    public function testExists()
    {
        $cache = $this->object;
        $key = __METHOD__;

        $cache->remove($key);
        $this->assertFalse($cache->exists($key));

        $cache->set($key, 'value');
        $this->assertTrue($cache->exists($key));

        // The key is exists and the value is "false"
        $cache->set($key, false);
        $this->assertTrue($cache->exists($key));
    }

    public function testGetAndSetMulti()
    {
        $items = array();
        foreach ($this->providerForGetterAndSetter() as $row) {
            $items[$row[1]] = $row[0];
        }
        $cache = $this->object;

        $results = $cache->setMulti($items);
        foreach ($results as $result) {
            $this->assertTrue($result);
        }

        $this->assertEquals($items, $cache->getMulti(array_keys($items)));
    }

    public function testCached()
    {
       $cache = $this->object;
       $num = 1;

       $result = $cache->cached(function() use($num){
           return ++$num;
       }, 60);

       $this->assertEquals(2, $result);

       // receive from cache
       $result2 = $cache->cached(function() use($num){
           return ++$num;
       }, 60);

       $this->assertEquals(2, $result2);
    }
}