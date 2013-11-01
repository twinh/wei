<?php

namespace WeiTest;

class JsonTest extends TestCase
{
    public function testJson()
    {
        $this->expectOutputString('{"code":-1,"message":"error"}');
        
        $this->json('error', -1);
    }
    
    public function testJsonp()
    {
        $this->request->set('callback', 'callback');
        
        $this->expectOutputString('callback({"code":-1,"message":"error"})');
        
        $this->json('error', -1, array(), true);
    }
    
    public function testCustomKey()
    {
        $this->json->setOption('code', 'ret_code');
        $this->json->setOption('message', 'ret_msg');
        
        $this->expectOutputString('{"ret_code":1,"ret_msg":"ok"}');
        
        $this->json('ok', 1);
    }
    
    public function testAppendData()
    {
        $this->expectOutputString('{"code":0,"message":"success","data":{"id":"1","title":"Title"}}');
        
        $this->json('success', 0, array(
            'data' => array(
                'id' => '1',
                'title' => 'Title'
            )
        ));
    }
}
