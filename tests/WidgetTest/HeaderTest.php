<?php

namespace WidgetTest;

class HeaderTest extends TestCase
{
    /**
     * @var \Widget\Header
     */
    protected $object;

    public function testInvoker()
    {
        $header = $this->object;

        $header('foo', 'bar');
        $this->assertEquals('bar', $header('foo'));
    }

    public function testGet()
    {
        $header = $this->object;

        $header->set('foo', 'bar');

        $this->assertEquals('bar', $header->get('foo'));

        $header->remove('foo');
        $this->assertNull($header->get('foo'));
    }

    public function testAppendHeader()
    {
        $header = $this->object;

        $header->remove('foo');

        $header->set('foo', 'bar');

        // append
        $header->set('foo', 'bar', false);

        $this->assertEquals(array('bar', 'bar'), $header->get('foo', null, false));
    }

    public function testToString()
    {
        $header = $this->object;

        $header->remove('foo');

        $header->set('foo', 'bar');

        // append
        $header->set('foo', 'bar', false);

        $this->assertEquals("foo: bar\r\nfoo: bar\r\n", (string)$header);
    }

    public function testSetArray()
    {
        $this->header->set(array(
            'foo' => 'bar',
            'foo2' => 'bar2'
        ));

        $this->assertEquals('bar', $this->header->get('foo'));
        $this->assertEquals('bar2', $this->header->get('foo2'));
    }
}
