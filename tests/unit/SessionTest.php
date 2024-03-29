<?php

namespace WeiTest;

/**
 * @internal
 */
final class SessionTest extends TestCase
{
    /**
     * @var \Wei\Session
     */
    protected $object;

    protected function setUp(): void
    {
        if (headers_sent($file, $line)) {
            $this->markTestSkipped(sprintf('Unable to start session, output started at %s:%s', $file, $line));
        }

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->object->destroy();

        parent::tearDown();
    }

    public function testSet()
    {
        $session = $this->object;

        $session->set('action', 'test');

        $this->assertEquals('test', $session->get('action'));
    }

    public function testClear()
    {
        $session = $this->object;

        $session->set('action', 'clear');

        $session->clear();

        $this->assertNull($session->get('action'));
    }

    public function testDestroy()
    {
        $session = $this->object;

        $session->set('action', 'clear');

        $session->destroy();

        $this->assertNull($session->get('action'));
    }

    public function testForEach()
    {
        $session = $this->object;
        $session->set('test', __FUNCTION__);

        foreach ($session as $key => $value) {
            $this->assertEquals($value, $session->get($key));
        }
    }

    /**
     * @dataProvider providerForGetterAndSetter
     * @param mixed $value
     * @param mixed $key
     */
    public function testValues($value, $key)
    {
        $this->session($key, $value);
        $this->assertEquals($value, $this->session($key));

        $this->session->set($key, $value);
        $this->assertEquals($value, $this->session->get($key));
    }

    public static function providerForGetterAndSetter()
    {
        $obj = new \stdClass();

        return [
            [[],  'array'],
            [true,     'bool'],
            [1.2,      'float'],
            [1,        'int'],
            [1,        'integer'],
            [null,     'null'],
            ['1',      'numeric'],
            [$obj,     'object'],
        ];
    }

    public function testArrayAccess()
    {
        $session = $this->object;

        $session['a'] = [];
        $session['a']['b'] = 'c';

        $this->assertEquals('c', $session['a']['b']);
    }

    public function testSetValueOnNonExistArray()
    {
        $session = $this->object;

        $session['a']['b'] = 'c';
        $this->assertEquals('c', $session['a']['b']);

        $session['d']['e']['f']['g'] = 'h';
        $this->assertEquals('h', $session['d']['e']['f']['g']);
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function testNamespace()
    {
        $this->session->setOption('namespace', 'test');
        $this->session->start();

        $this->session('k', 'v');
        $this->assertEquals('v', $this->session('k'));
        $this->assertArrayNotHasKey('k', $_SESSION);
        $this->assertArrayHasKey('test', $_SESSION);
    }

    public function testRestart()
    {
        $this->session['test'] = 'test';

        session_write_close();

        unset($this->session['test']);
        $this->assertNull($this->session['test']);

        $this->session->start();
        $this->assertEquals('test', $this->session['test']);
    }
}
