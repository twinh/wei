<?php

namespace WeiTest;

/**
 * @internal
 */
final class RetTest extends TestCase
{
    public function testSuc()
    {
        $ret = wei()->ret->suc();
        $this->assertEquals([
            'message' => 'Operation successful',
            'code' => 1,
        ], $ret);
    }

    public function testErr()
    {
        $ret = wei()->ret->err('Operation failed');
        $this->assertEquals([
            'message' => 'Operation failed',
            'code' => -1,
        ], $ret);
    }

    public function testSucWithArray()
    {
        $ret = wei()->ret->suc([
            'message' => 'Payment successful',
            'amount' => '10.00',
            'data' => [
                'key' => 'value',
            ],
        ]);
        $this->assertEquals([
            'code' => 1,
            'message' => 'Payment successful',
            'amount' => '10.00',
            'data' => [
                'key' => 'value',
            ],
        ], $ret);
    }
}
