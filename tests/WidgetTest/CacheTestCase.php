<?php

namespace WidgetTest;

class CacheTestCase extends TestCase
{
    /**
     * @var \Widget\Stdlib\AbstractCache
     */
    protected $object;

    protected function setUp()
    {
        parent::setUp();
        $this->object->setKeyPrefix('test-');
    }

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

        // Replace with the same value for testing MySQL cache
        $this->assertTrue($cache->replace($key, $value));

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

    public function testIncAndDec()
    {
        $cache = $this->object;

        $key = __METHOD__;
        $cache->remove($key);

        // Increase from not exists key
        $this->assertSame(2, $cache->inc($key, 2));
        $this->assertEquals(2, $cache->get($key));

        // Increase from exists key and the offset is 3
        $this->assertSame(5, $cache->inc($key, 3));
        $this->assertEquals(5, $cache->get($key));

        $this->assertSame(3, $cache->dec($key, 2));
        $this->assertEquals(3, $cache->get($key));

        // Negative number
        $this->assertSame(1, $cache->inc($key, -2));
        $this->assertEquals(1, $cache->get($key));

        $this->assertSame(3, $cache->dec($key, -2));
        $this->assertEquals(3, $cache->get($key));
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

    public function testGetFalseResultAndSet()
    {
        $cache = $this->object;
        $num = 1;
        $key = __METHOD__;

        $cache->clear();

        $result = $cache->get($key, 60, function() use($num){
            return ++$num;
        });

        $this->assertEquals(2, $result);

        // receive from cache
        $result2 = $cache->get($key, function() use($num){
            return ++$num;
        });

        $this->assertEquals(2, $result2);
    }

    public function testKeyPrefix()
    {
        $cache = $this->object;
        $cache->setKeyPrefix('prefix-');
        $this->assertEquals('prefix-', $cache->getKeyPrefix());

        $cache->set('test', 1);
        $this->assertEquals(1, $cache->get('test'));
        $this->assertSame(3, $cache->inc('test', 2));
        $this->assertSame(1, $cache->dec('test', 2));

        $this->assertEquals(2, $cache->replace('test', 2));
        $this->assertEquals(2, $cache->get('test'));

        $this->assertTrue($cache->remove('test'));
        $this->assertTrue($cache->add('test', 3));
        $this->assertEquals(3, $cache->get('test'));

        $this->assertEquals(array('test' => 3), $cache->getMulti(array('test')));

        $result = $cache->setMulti(array('test' => 2));
        $this->assertEquals(array('test' => true), $result);

        $this->assertEquals(2, $cache->get('test'));
    }
}