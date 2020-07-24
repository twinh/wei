<?php

namespace WeiTest;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Wei\Base
     */
    protected $object;

    /**
     * The service container
     *
     * @var \Wei\Wei
     */
    protected $wei;

    /**
     * The service name of current test case
     *
     * @var string
     */
    protected $serviceName;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->wei = wei();
    }

    /**
     * Invoke the service by the given name
     *
     * @param string $name The name of wei
     * @param array $args The arguments for wei's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->{$name}, $args);
    }

    /**
     * Get the service object by the given name
     *
     * @param string $name The name of wei
     * @return \Wei\Base
     */
    public function __get($name)
    {
        return $this->wei->get($name);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $wei = $this->wei;
        $name = $this->getServiceName();

        if ('wei' == strtolower($name)) {
            return;
        }

        if ($wei->has($name)) {
            // Re-instance
            $this->object = $wei->{lcfirst($name)};
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $wei = $this->wei;
        $name = $this->getServiceName();

        if ('wei' == strtolower($name)) {
            return;
        }

        foreach ($wei->getServices() as $key => $value) {
            if ('wei' == $key) {
                continue;
            }
            $wei->remove($key);
        }

        foreach (get_object_vars($this->wei) as $name => $property) {
            // Preserve the service container
            if ('wei' == $name) {
                continue;
            }

            // Remove all service instanced by current test object
            if ($property instanceof \Wei\Base) {
                unset($this->{$name});
                $wei->remove($name);
            }
        }

        $this->object = null;

        if (isset($wei->{$name})) {
            unset($wei->{$name});
        }
    }

    public function assertArrayBehaviour($arr)
    {
        // Behaviour 1
        $arr['a']['b'] = true;
        $this->assertTrue($arr['a']['b'], 'Assign multi level array directly');

        // Behaviour 2
        $hasException = false;
        try {
            $arr['b'];
        } catch (\Exception $e) {
            if ('Undefined index: b' == $e->getMessage()) {
                $hasException = true;
            } else {
                throw $e;
            }
        }
        if ('array' == gettype($arr)) {
            $this->assertTrue($hasException, 'Access array\'s undefined index would cause error');
        } else {
            $this->assertFalse($hasException, 'Access object\'s undefined index won\'t cause error');
        }

        $this->assertFalse(isset($arr['b']), 'Access undefined index won\'t create key');

        // Behaviour 3
        $arr['c'] = [];
        $arr['c']['d'] = 'e';
        $this->assertEquals('e', $arr['c']['d'], 'Allow to create next level array');

        // Behaviour 4
        unset($arr['d']);
        $this->assertFalse(isset($arr['d']));

        $arr['d'] = null;
        $this->assertFalse(isset($arr['d']), 'Call isset returns false when value is null');

        // Behaviour 5
        if (method_exists($arr, 'toArray')) {
            $origArr = $arr->toArray();
        } else {
            $origArr = $arr;
        }
        $this->assertArrayHasKey('d', $origArr, 'Call array_key_exists returns true even if value is null');
    }

    public function assertRetSuc(array $ret, $message = null, $assertMessage = null)
    {
        $assertMessage = $this->buildRetMessage($ret, $assertMessage);

        $expected = ['code' => 1];
        if (null !== $message) {
            $expected['message'] = $message;
        }

        $this->assertArrayContains($expected, $ret, $assertMessage);
    }

    public function assertRetErr(array $ret, $code, $message = null, $assertMessage = null)
    {
        $assertMessage = $this->buildRetMessage($ret, $assertMessage);

        $expected = ['code' => $code];
        if (null !== $message) {
            $expected['message'] = $message;
        }

        $this->assertArrayContains($expected, $ret, $assertMessage);
    }

    public static function assertArrayContains($subset, $array, $message = '')
    {
        $array = array_intersect_key($array, array_flip(array_keys($subset)));
        parent::assertEquals($subset, $array, $message);
    }

    /**
     * Get the wei name
     *
     * @return string
     */
    protected function getServiceName()
    {
        if (empty($this->serviceName)) {
            $names = explode('\\', static::class);
            $class = array_pop($names);
            $this->serviceName = substr($class, 0, -4);
        }
        return $this->serviceName;
    }

    /**
     * Asserts that an array is contains another array
     *
     * @param array $subset
     * @param array $parent
     * @param string $message
     * @return void
     */
    protected function assertIsSubset($subset, $parent, $message = '')
    {
        if (!(is_array($parent) && $subset)) {
            $this->assertTrue(false, $message);
            return;
        }

        foreach ($subset as $item) {
            if (!in_array($item, $parent, true)) {
                $this->assertTrue(false, $message);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @param string $class
     * @param string $message
     * @deprecated
     */
    protected function setExpectedException($class, $message = null)
    {
        $this->expectException($class);
        $message && $this->expectExceptionMessage($message);
    }

    protected function buildRetMessage($ret, $assertMessage = null)
    {
        return $assertMessage . ' ret is ' . json_encode($ret, JSON_UNESCAPED_UNICODE);
    }
}
