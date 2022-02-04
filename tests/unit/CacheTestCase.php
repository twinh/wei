<?php

namespace WeiTest;

use WeiTest\Fixtures\CacheObject;

abstract class CacheTestCase extends TestCase
{
    /**
     * @var \Wei\BaseCache
     */
    protected $object;

    protected function setUp(): void
    {
        parent::setUp();
        $this->object->setNamespace('test-');
    }

    /**
     * @dataProvider providerForGetterAndSetter
     * @param mixed $value
     * @param mixed $key
     */
    public function testGetterAndSetter($value, $key)
    {
        $cache = $this->object;

        $cache->delete($key);
        $this->assertNull($cache->get($key));

        $this->assertFalse($cache->replace($key, $value));
        $this->assertTrue($cache->add($key, $value));

        $cache->set($key, $value);

        if (is_float($value) && is_nan($value)) {
            $this->assertNan($cache->get($key));
        } elseif (is_scalar($value)) {
            $this->assertSame($value, $cache->get($key));
        } else {
            $this->assertEquals($value, $cache->get($key));
        }

        $this->assertFalse($cache->add($key, $value));

        // Replace with the same value for testing MySQL cache
        $this->assertTrue($cache->replace($key, $value));

        $this->assertTrue($cache->replace($key, uniqid()));
    }

    public function getFixtures(): array
    {
        $object = new CacheObject('test');
        return [
            [[], 'array'],
            [true, 'bool'],
            [1.2, 'float'],
            [1.0, 'float-int'],
            [7E+20, 'float-e'],
            [7E-20, 'float-e2'],
            [\INF, 'float-inf'],
            [-\INF, 'float-ninf'],
            [1, 'int'],
            [null, 'null'],
            ['1', 'numeric'],
            [$object, 'object'],
        ];
    }

    public function providerForGetterAndSetter()
    {
        $types = $this->getFixtures();
        $types[] = [\NAN, 'nan'];
        return $types;
    }

    public function testIncrAndDecr()
    {
        $cache = $this->object;

        $key = __METHOD__;
        $cache->delete($key);

        // Increase from not exists key
        $this->assertSame(2, $cache->incr($key, 2));
        $this->assertEquals(2, $cache->get($key));

        // Increase from exists key and the offset is 3
        $this->assertSame(5, $cache->incr($key, 3));
        $this->assertEquals(5, $cache->get($key));

        $this->assertSame(3, $cache->decr($key, 2));
        $this->assertEquals(3, $cache->get($key));

        // Negative number
        $this->assertSame(1, $cache->incr($key, -2));
        $this->assertEquals(1, $cache->get($key));

        $this->assertSame(3, $cache->decr($key, -2));
        $this->assertEquals(3, $cache->get($key));
    }

    public function testClear()
    {
        $cache = $this->object;
        $key = __METHOD__;

        $cache->set($key, $key);
        $this->assertEquals($key, $cache->get($key));

        $cache->clear();
        $this->assertNull($cache->get($key));
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

        $cache->delete($key);
        $this->assertFalse($cache->has($key));

        $cache->set($key, 'value');
        $this->assertTrue($cache->has($key));

        // The key is exists and the value is "false"
        $cache->set($key, false);
        $this->assertTrue($cache->has($key));
    }

    public function testHas()
    {
        $cache = $this->object;
        $key = __METHOD__;

        $cache->delete($key);
        $this->assertFalse($cache->has($key));

        $cache->set($key, 'value');
        $this->assertTrue($cache->has($key));

        // The key is exists and the value is "false"
        $cache->set($key, false);
        $this->assertTrue($cache->has($key));
    }

    public function testGetAndSetMulti()
    {
        $items = [];
        foreach ($this->getFixtures() as $row) {
            $items[$row[1]] = $row[0];
        }
        $cache = $this->object;

        $results = $cache->setMulti($items);
        foreach ($results as $result) {
            $this->assertTrue($result);
        }

        $this->assertEquals($items, $cache->getMulti(array_keys($items)));
    }

    public function testGetAndSetMultiple()
    {
        $items = [];
        foreach ($this->getFixtures() as $row) {
            $items[$row[1]] = $row[0];
        }
        $cache = $this->object;

        $result = $cache->setMultiple($items);
        $this->assertTrue($result);

        $this->assertEquals($items, $cache->getMultiple(array_keys($items)));
    }

    public function testGetNullResultAndSet()
    {
        $cache = $this->object;
        $num = 1;
        $key = __METHOD__;

        $cache->clear();

        $result = $cache->remember($key, 60, function () use ($num) {
            return ++$num;
        });

        $this->assertEquals(2, $result);

        // receive from cache
        $result2 = $cache->remember($key, function () use ($num) {
            return ++$num;
        });

        $this->assertEquals(2, $result2);

        $result3 = $cache->get($key, function ($wei, $cache) {
            $this->assertInstanceOf('\Wei\Wei', $wei);
            $this->assertInstanceOf('\Wei\BaseCache', $cache);
            return 2;
        });
        $this->assertEquals(2, $result3);
    }

    public function testPrefix()
    {
        $cache = $this->object;
        $cache->setNamespace('prefix-');
        $this->assertEquals('prefix-', $cache->getNamespace());

        $cache->set('test', 1);
        $this->assertEquals(1, $cache->get('test'));
        $this->assertSame(3, $cache->incr('test', 2));

        $this->assertSame(1, $cache->decr('test', 2));

        $this->assertEquals(2, $cache->replace('test', 2));
        $this->assertEquals(2, $cache->get('test'));

        $this->assertTrue($cache->delete('test'));
        $this->assertTrue($cache->add('test', 3));
        $this->assertEquals(3, $cache->get('test'));

        $this->assertEquals(['test' => 3], $cache->getMultiple(['test']));

        $result = $cache->setMultiple(['test' => 2]);
        $this->assertTrue($result);

        $this->assertEquals(2, $cache->get('test'));
    }

    public function testGetFileContent()
    {
        $file = __DIR__ . '/Fixtures/User.php';
        $cache = $this->object->getFileContent($file, function ($file) {
            return file_get_contents($file);
        });
        $cache2 = $this->object->getFileContent($file, function ($file) {
            return file_get_contents($file);
        });
        $this->assertEquals($cache, $cache2);
    }

    public function testRemove()
    {
        $result = $this->object->set('key', 'value');
        $this->assertTrue($result);

        $this->assertTrue($this->object->remove('key'));
        $this->assertFalse($this->object->remove('key'));
    }

    public function testDelete()
    {
        $result = $this->object->set('key', 'value');
        $this->assertTrue($result);

        $this->assertTrue($this->object->delete('key'));
        $this->assertFalse($this->object->delete('key'));
    }

    public function testRemember()
    {
        $this->object->set('test', 'value');

        $callback = function () use (&$called) {
            $called = true;
            return 'value2';
        };

        $called = false;
        $result = $this->object->remember('test', $callback);

        $this->assertSame('value', $result);
        $this->assertFalse($called);

        $this->object->delete('test');
        $result = $this->object->remember('test', $callback);
        $this->assertSame('value2', $result);
        $this->assertTrue($called);
    }

    public function testRememberWithExpire()
    {
        $this->object->delete('test');
        $result = $this->object->remember('test', 1, function () {
            return 'value';
        });
        $this->assertSame('value', $result);
    }

    public function testRememberInvalidExpireTime()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expire time for cache "key" must be int, NULL given');

        $this->object->setNamespace('');
        $this->object->remember('key', null, function () {
        });
    }

    public function testIsHit()
    {
        $this->object->set('test', 'value');

        $this->object->get('test');
        $this->assertTrue($this->object->isHit());

        $this->object->delete('test');
        $this->object->get('test');
        $this->assertFalse($this->object->isHit());
    }

    public function testIsHitWithNull()
    {
        $this->object->set('test', null);

        $this->object->get('test');
        $this->assertTrue($this->object->isHit());
    }

    public function testIsHitMultiple()
    {
        $this->object->delete('test');
        $this->object->delete('test2');
        $this->object->delete('test3');

        $this->object->getMultiple(['test', 'test2', 'test3']);

        $this->assertFalse($this->object->isHit('test'));
        $this->assertFalse($this->object->isHit('test2'));
        $this->assertFalse($this->object->isHit('test3'));

        $this->object->setMultiple([
            'test' => 'value',
            'test2' => null,
        ]);

        $this->object->getMultiple(['test', 'test2', 'test3']);
        $this->assertTrue($this->object->isHit('test'));
        $this->assertTrue($this->object->isHit('test2'));
        $this->assertFalse($this->object->isHit('test3'));
    }
}
