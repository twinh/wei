<?php

namespace WidgetTest;

class UuidTest extends TestCase
{
    public function testUuid()
    {
        for ($i = 0; $i < 100; $i++) {
            $this->isUuid($this->uuid());
        }
    }
}