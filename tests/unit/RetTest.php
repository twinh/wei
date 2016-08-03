<?php

namespace WeiTest;

class RetTest extends TestCase
{
    public function testSuc()
    {
        $ret = wei()->ret->suc();
        $this->assertEquals(array(
            'message' => 'Operation successful',
            'code' => 1,
        ), $ret);
    }

    public function testErr()
    {
        $ret = wei()->ret->err('Operation failed');
        $this->assertEquals(array(
            'message' => 'Operation failed',
            'code' => -1,
        ), $ret);
    }

    public function testSucWithArray()
    {
        $ret = wei()->ret->suc(array(
            'message' => 'Payment successful',
            'amount' => '10.00',
            'data' => array(
                'key' => 'value'
            )
        ));
        $this->assertEquals(array(
            'code' => 1,
            'message' => 'Payment successful',
            'amount' => '10.00',
            'data' => array(
                'key' => 'value'
            )
        ), $ret);
    }
}