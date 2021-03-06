<?php

namespace WeiTest;

/**
 * @internal
 */
final class EventTest extends TestCase
{
    /**
     * @var \Wei\Event
     */
    protected $object;

    public function testEvent()
    {
        $event = $this->object;

        $results = $event->off('test')
            ->on('test', function () {
                return 'a';
            })
            ->on('test', function () {
                return 'b';
            })
            ->trigger('test');

        $this->assertEquals(['a', 'b'], $results);
    }

    public function testAddHandler()
    {
        $this->object->on('test', function () {
        });

        $this->assertTrue($this->object->has('test'));
    }

    public function testAddInvalidHandler()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->object->on('test', 'not callback');
    }

    public function testTriggerHandler()
    {
        $results = $event = $this->object->off('test')
            ->on('test', function () {
                return false;
            })
            ->trigger('test');
        $this->assertFalse($results[0]);

        $results = $this->object->off('test')
            ->on('test', function () {
                return false;
            })
            // won't be triggered
            ->on('test', function () {
                return 'second';
            })
            ->trigger('test');
        $this->assertEquals([false], $results);
    }

    public function testHasHandler()
    {
        $fn = function () {
        };
        $event = $this->object;

        $this->object->off('test')
            ->off('test.before')
            ->on('test', $fn)
            ->on('test.before', $fn);

        $this->assertTrue($event->has('test'));

        $this->assertTrue($event->has('test.before'));

        $this->assertFalse($event->has('test2'));

        $this->assertFalse($event->has('test2.ns3'));
    }

    public function testRemoveHandler()
    {
        $event = $this->object;

        $init = function () {
            $fn = function () {
            };
            $this->event->off('test')
                ->on('test', $fn)
                ->on('test.before', $fn)
                ->on('test.after', $fn)
                ->on('test.before.ns2', $fn);
        };

        $init();
        $this->assertTrue($event->has('test'));
        $this->object->off('test');
        $this->assertFalse($event->has('test'));

        $init();
        $this->assertTrue($event->has('test.before'));
        $this->object->off('test.before');
        $this->assertFalse($event->has('test.before'));

        $init();
        $this->assertTrue($event->has('test.before.ns2'));
        $this->object->off('test.before.ns2');
        $this->assertFalse($event->has('test.before.ns2'));
    }

    public function testArrayAsOnParameters()
    {
        $this->object->off('test')
            ->on([
                'test.before' => function () {
                },
                'test.after' => function () {
                },
            ]);

        $this->assertTrue($this->object->has('test.before'));
        $this->assertTrue($this->object->has('test.after'));
    }

    public function testArrayArgs()
    {
        $this->object->on('test', function ($arg) {
            return $arg;
        });

        $result = $this->object->trigger('test', ['test']);

        $this->assertEquals(['test'], $result);
    }

    public function testNotArrayArgs()
    {
        $this->object->on('test', function ($arg) {
            return $arg;
        });

        $result = $this->object->trigger('test', 'test');

        $this->assertEquals(['test'], $result);
    }

    public function testUntil()
    {
        $this->object->on(__FUNCTION__, function () {
            return null;
        });

        $this->object->on(__FUNCTION__, function () {
            return 'here';
        });

        $this->object->on(__FUNCTION__, function () {
            return 'not here';
        });

        $this->assertEquals('here', $this->object->until(__FUNCTION__));
    }
}
