<?php

namespace WeiTest;

/**
 * @internal
 */
final class UuidTest extends TestCase
{
    protected $testCount = 10;

    public function testUuid()
    {
        for ($i = 0; $i < $this->testCount; ++$i) {
            $this->assertTrue($this->isUuid($this->uuid()));
        }
    }
}
