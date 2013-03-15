<?php

namespace WidgetTest;

class QueryTest extends TestCase
{
    public function createQuery()
    {
        // create request widget from custom parameter
        $request = new \Widget\Request(array(
            'fromGlobal' => false,
            'get' => array(
                'key' => 'value',
                'key2' => 'value2',
                'int' => '5',
                'array' => array(
                    'item' => 'value'
                )
            )
        ));
        
        // inject request widget
        $query = new \Widget\Query(array(
            'request' => $request
        ));
        
        return $query;
    }
    
    public function testGetter()
    {
        $query = $this->createQuery();
        
        $this->assertEquals('value', $query->get('key'));
        
        $this->assertEquals(5, $query->getInt('int'));
        
        $this->assertEquals(array('5'), $query->getArray('int'));
        
        // int => 5, not in specified array
        $this->assertEquals('firstValue', $query->getInArray('int', array(
            'firstKey' => 'firstValue',
            'secondKey' => 'secondValue'
        )));
        
        $this->assertEquals(6, $query->getInt('int', 6));
    }
}