<?php

namespace WidgetTest;

class UrlTest extends TestCase
{
    public function testOnePart()
    {
        $url = $this->url('user', array('id' => 'twin'));
        
        $this->assertEquals('?controller=user&id=twin', $url);
    }
    
    public function testTwoPart()
    {
        $url = $this->url('user/edit', array('id' => 'twin'));
        
        $this->assertEquals('?controller=user&action=edit&id=twin', $url);
    }
    
    public function testModule()
    {
        $url = $this->url('api/user/edit', array('id' => 'twin'));
        
        $this->assertEquals('?module=api&controller=user&action=edit&id=twin', $url);
    }
    
    public function testStringAsParameter()
    {
        $url = $this->url('user', 'id=twin&from=test');
        
        $this->assertEquals('?controller=user&id=twin&from=test', $url);
    }
    
    public function testMultiParameters()
    {
        $url = $this->url('user', 'id=twin', 'from=test');
        
        $this->assertEquals('?controller=user&id=twin&from=test', $url);
    }
}
