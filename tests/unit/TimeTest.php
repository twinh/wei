<?php

namespace WeiTest;

use Wei\Time;

/**
 * @internal
 */
final class TimeTest extends TestCase
{
    public function testTimestamp()
    {
        $this->assertIsInt(Time::timestamp());
        $this->assertGreaterThanOrEqual(time(), Time::timestamp());
    }

    public function testToday()
    {
        $this->assertEquals(date('Y-m-d'), Time::today());
    }

    public function testNow()
    {
        $this->assertLessThanOrEqual(date('Y-m-d H:i:s'), Time::now());
    }

    public function testSetTimestamp()
    {
        $time = new Time();
        $time->setTimestamp(1234567890);
        $this->assertEquals(1234567890, $time->timestamp());
    }
}
