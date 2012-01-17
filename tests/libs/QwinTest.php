<?php

require_once dirname(__FILE__) . '/../../libs/Qwin.php';

/**
 * Test class for Qwin.
 * Generated by PHPUnit on 2012-01-16 at 08:33:55.
 */
class QwinTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Qwin
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = Qwin::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Qwin::autoload
     */
    public function testAutoload() {
        $this->assertEquals(class_exists('Qwin_Get', false), false, 'Class "Qwin_Get" not found.');
        
        $this->object->autoload('Qwin_Get');
        
        $this->assertEquals(class_exists('Qwin_Get'), true, 'Class "Qwin_Get" found.');
    }

    /**
     * @covers Qwin::widget
     */
    public function testWidget() {
        $get = $this->object->get;
        $this->assertEquals(get_class($get), 'Qwin_Get', 'Class of Widget "get" is "Qwin_Get"');
        
    }

    /**
     * @covers Qwin::call
     */
    public function testCall() {
        $std = $this->object->call('stdClass');
        
        $this->assertEquals(get_class($std), 'stdClass', 'Init class stdClass');
    }

    /**
     * @covers Qwin::config
     */
    public function testConfig() {
        // clean all config
        $this->object->config(array());
        
        $this->assertEmpty($this->object->config(), 'Config is empty');
    }

    /**
     * @covers Qwin::variable
     */
    public function testVariable() {
        $var = Qwin::variable('var');
        
        $expected = new Qwin_Widget('var');
        
        $this->assertEquals($expected, $var);
    }

    /**
     * @covers Qwin::getInstance
     */
    public function testGetInstance() {
        
        $this->assertEquals(Qwin::getInstance(), $this->object, 'Class only has one instance');
    }

    /**
     * @covers Qwin::callWidget
     */
    public function testCallWidget() {
        $this->assertEquals(class_exists('Qwin_Post', false), false, 'Class "Qwin_Post" not found.');
        
        $name = $this->object->callWidget($this->object, 'post', array('name'));
        
        $this->assertEquals(class_exists('Qwin_Post'), true, 'Class "Qwin_Post" found.');
    }

    /**
     * @covers Qwin::__invoke
     */
    public function test__invoke() {
        $widget = $this->object->__invoke('this is a string.');
        
        $this->assertEquals(get_class($widget), 'Qwin_Widget');
    }

}