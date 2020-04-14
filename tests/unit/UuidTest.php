<?php

namespace WeiTest;

class UuidTest extends TestCase
{
    public function testUuid()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->assertTrue($this->isUuid($this->uuid()));
        }
    }
}
