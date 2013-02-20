<?php

namespace WidgetTest;

class AttrTest extends TestCase
{
    public function testArray()
    {
        $array = array(
            'key' => 'value',
            1 => '10',
            11,
        );
        
        $arrayObject = new \ArrayObject($array);
        
        foreach ($array as $key => $value) {
            $this->assertEquals($value, $this->attr($array, $key));
            $this->assertEquals($value, $this->attr($arrayObject, $key));
        }
    }
    
    public function testPropertyAndGetter()
    {
        $this->assertEquals('pv', $this->attr($this, 'propertyValue'));
        $this->assertEquals('value', $this->attr($this, 'value'));
        $this->assertEquals(null, $this->attr($this, 'no this property'));
    }
    
    /**
     * test fixture for testPropertyAndGetter()
     */
    public $propertyValue = 'pv';
    public function getValue()
    {
        return 'value';
    }
}
