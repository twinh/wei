<?php

namespace WidgetTest;

class SessionTest extends TestCase
{
    /**
     * @var \Widget\Session
     */
    protected $object;

    protected function setUp()
    {
        parent::setUp();
        $data = array();
        session_set_save_handler(
        // open
            function(){
                return true;
            },
            // close
            function(){
                return true;
            },
            // read
            function($id) use($data){
                return $data[$id];
            },
            // write
            function($id, $value) use($data){
                $data[$id] = $value;
                return true;
            },
            // destroy
            function(){
                return true;
            },
            // gc
            function(){
                return true;
            }
        );
    }

    protected function tearDown()
    {
        // FIXME why sometime $this->obejct is NULL
        if ($this->object) {
            $this->object->destroy();
        }

        parent::tearDown();
    }

    /**
     * FIXME remove @runInSeparateProcess for travis-ci
     *
     * @runInSeparateProcess
     */
    public function testSet()
    {
        $session = $this->object;

        $session->set('action', 'test');

        $this->assertEquals('test', $session->get('action'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testClear()
    {
        $session = $this->object;

        $session->set('action', 'clear');

        $session->clear();

        $this->assertEquals(null, $session->get('action'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroy()
    {
        $session = $this->object;

        $session->set('action', 'clear');

        $session->destroy();

        $this->assertEquals(null, $session->get('action'));
    }
    /**
     * @dataProvider providerForGetterAndSetter
     * @runInSeparateProcess
     */
    public function testValues($value, $key)
    {
        $this->session($key, $value);
        $this->assertEquals($value, $this->session($key));

        $this->session->set($key, $value);
        $this->assertEquals($value, $this->session->get($key));
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