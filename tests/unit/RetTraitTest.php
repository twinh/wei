<?php

namespace WeiTest;

use Wei\RetTrait;

class RetTraitTest extends TestCase
{
    use RetTrait;

    public function testSuc()
    {
        $this->assertRetSuc($this->suc());
    }

    public function testErr()
    {
        $this->assertRetErr($this->err('Error!', 2), 'Error!', 2);
    }

    public function testRet()
    {
        $this->assertRetSuc($this->ret('message', null));

        $this->assertRetSuc($this->ret('message', 0));

        $this->assertRetErr($this->ret('message', 2), 'message', 2);
    }
}
