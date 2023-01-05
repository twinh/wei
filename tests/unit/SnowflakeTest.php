<?php

namespace WeiTest;

use Wei\Snowflake;

class SnowflakeTest extends TestCase
{
    public function testReturnType()
    {
        $id = Snowflake::next();
        $this->assertIsString($id);
        $this->assertIsNumeric($id);
    }

    public function testOrder()
    {
        $id = Snowflake::next();
        $id2 = Snowflake::next();
        $id3 = Snowflake::next();

        $this->assertGreaterThan($id, $id2);
        $this->assertGreaterThan($id2, $id3);
    }

    public function testParse()
    {
        $snowflake = new Snowflake([
            'workerId' => 1,
            'randomStartSequence' => false,
        ]);

        $id = $snowflake->next();
        $id2 = $snowflake->next();
        $result = $snowflake->parse($id);
        $result2 = $snowflake->parse($id2);

        $timestamp = (int) (microtime(true) * 1000);
        $this->assertLessThanOrEqual($timestamp, $result['timestamp']);
        $this->assertLessThanOrEqual($timestamp, $result2['timestamp']);

        $this->assertLessThan(100, $timestamp - $result['timestamp']);
        $this->assertLessThan(100, $timestamp - $result2['timestamp']);

        $this->assertSame(1, $result['workerId']);
        $this->assertSame(1, $result2['workerId']);

        $this->assertSame(0, $result['sequence']);
        $this->assertTrue(in_array($result2['sequence'], [0, 1], true));
    }

    public function testParseWorkerId()
    {
        $workerId = mt_rand(0, 1023);
        $snowflake = new Snowflake([
            'workerId' => $workerId,
        ]);

        $id = $snowflake->next();

        $this->assertSame($workerId, $snowflake->getWorkerId());
        $this->assertSame($snowflake->getWorkerId(), $snowflake->parse($id)['workerId']);
    }

    public function testParseShorterId()
    {
        $startTimestamp = time() * 1000;
        $snowflake = new Snowflake([
            'startTimestamp' => $startTimestamp,
            'workerId' => 1,
            'randomStartSequence' => false,
        ]);

        $id = $snowflake->next();
        $this->assertLessThanOrEqual(10, strlen($id));

        $result = $snowflake->parse($id);
        $this->assertGreaterThanOrEqual($startTimestamp, $result['timestamp']);
        $this->assertSame(1, $result['workerId']);
        $this->assertSame(0, $result['sequence']);
    }

    public function testWorkerId()
    {
        $snowflake = new Snowflake([
            'workerId' => 1,
        ]);

        $this->expectExceptionObject(new \InvalidArgumentException('Worker ID must be greater than 0'));
        $snowflake->setWorkerId(-1);

        $this->expectExceptionObject(new \InvalidArgumentException('Worker ID must be less than or equal to 1023'));
        $snowflake->setWorkerId(1024);

        $this->assertSame(1, $snowflake->getWorkerId());

        $snowflake->setWorkerId(1023);
        $this->assertSame(1023, $snowflake->getWorkerId());
    }

    public function testStartTimestamp()
    {
        $snowflake = new Snowflake([
            'startTimestamp' => 0,
        ]);

        $this->expectExceptionObject(new \InvalidArgumentException('Start timestamp must be greater than 0'));
        $snowflake->setStartTimestamp(-1);

        $this->expectExceptionObject(new \InvalidArgumentException(
            'Start timestamp must be less than or equal to to the current time'
        ));
        $snowflake->setStartTimestamp(time() * 1000 + 1);

        $this->assertSame(0, $snowflake->getStartTimestamp());

        $now = time() * 1000;
        $snowflake->setStartTimestamp($now);
        $this->assertSame($now, $snowflake->getStartTimestamp());
    }
}
