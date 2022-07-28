<?php

namespace WeiTest\Model;

use Wei\Event;
use WeiTest\Model\Fixture\TestEvent;
use WeiTest\Model\Fixture\TestEvent2;
use WeiTest\TestCase;

/**
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
class EventTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_events')
            ->id()
            ->timestamps()
            ->exec();

        TestEvent::save();
        TestEvent::save();
    }

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_events');
    }

    protected function tearDown(): void
    {
        Event::off('testEventModelAfterFind')->off('testEventModelBeforeSave');

        parent::tearDown();
    }

    public function testCreate()
    {
        $event = TestEvent::save();

        $this->assertSame('beforeSave->beforeCreate->afterCreate->afterSave', $event->getEventResult());
    }

    public function testUpdate()
    {
        $event = TestEvent::first()->save();

        $this->assertSame('afterFind->beforeSave->beforeUpdate->afterUpdate->afterSave', $event->getEventResult());
    }

    public function testDestroy()
    {
        $event = TestEvent::destroy(1);

        $this->assertSame('afterFind->beforeDestroy->afterDestroy', $event->getEventResult());
    }

    public function testFind()
    {
        $event = TestEvent::first();

        $this->assertSame('afterFind', $event->getEventResult());
    }

    public function testFindAll()
    {
        $events = TestEvent::limit(1)->all();

        $this->assertSame('afterFind', $events[0]->getEventResult());
    }

    public function testTimestamp()
    {
        $event = TestEvent::new();
        $this->assertNull($event->created_at);

        $event->save();
        $this->assertNotNull($event->created_at);
    }

    public function testOnModelEvent()
    {
        $result = '';
        $eventObject = null;
        TestEvent::onModelEvent('beforeSave', function ($event) use (&$result, &$eventObject) {
            $result = 'beforeSave';
            $eventObject = $event;
        });

        $event = TestEvent::save();

        $this->assertSame($event, $eventObject);
        $this->assertSame('beforeSave', $result);
    }

    public function testGlobalEvent()
    {
        $result = '';
        Event::on('testEventModelBeforeSave', function () use (&$result) {
            $result = 'custom';
        });

        TestEvent::save();

        $this->assertSame('custom', $result);
    }

    public function testCallMultiply()
    {
        $event = TestEvent::save()->save()->save();

        $result = $event->getEventResult();

        $this->assertSame(implode('->', [
            'beforeSave->beforeCreate->afterCreate->afterSave',
            'beforeSave->beforeUpdate->afterUpdate->afterSave',
            'beforeSave->beforeUpdate->afterUpdate->afterSave',
        ]), $result);
    }

    public function testCallSelfMethodFirst()
    {
        Event::on('testEventModelBeforeSave', function (TestEvent $event) {
            $event->addEventResult('custom');
        });

        $event = TestEvent::save();

        $this->assertSame(
            'beforeSave->custom->beforeCreate->afterCreate->afterSave',
            $event->getEventResult()
        );
    }

    public function testEventNameWontConflict()
    {
        $result = '';
        TestEvent::onModelEvent('afterFind', function () use (&$result) {
            $result .= 'afterFind';
        });

        TestEvent::first();
        $this->assertSame('afterFind', $result);

        TestEvent2::first();
        $this->assertSame('afterFind', $result, 'Should not trigger TestEvent afterFind');
    }

    public function testReturnFalseStopPropagation()
    {
        $result = '';
        TestEvent::onModelEvent('afterFind', function () use (&$result) {
            $result .= 'afterFind';
        });

        TestEvent::setAfterFindReturn(false);

        $event = TestEvent::first();
        $this->assertSame('afterFind', $event->getEventResult());

        $this->assertSame('', $result);
    }
}
