<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestSoftDeleteTrash;
use WeiTest\TestCase;

/**
 * @internal
 */
final class SoftDeleteTraitTrashTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_soft_delete_trashes')
            ->id()
            ->softDeletable()
            ->timestamp('purged_at')
            ->uBigInt('purged_by')
            ->exec();
    }

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        TestSoftDeleteTrash::resetBoot();
        parent::setUp();
    }

    public function testPurge()
    {
        $record = TestSoftDeleteTrash::save();
        // @codingStandardsIgnoreStart
        $this->assertNull($record->purged_at);
        // @codingStandardsIgnoreEnd

        $record->destroy();
        $record->purge();
        // @codingStandardsIgnoreStart
        $this->assertNotEmpty($record->purged_at);
        // @codingStandardsIgnoreEnd
    }

    public function testRestorePurge()
    {
        $record = TestSoftDeleteTrash::save();
        $record->destroy();
        $record->purge();
        $record->restorePurge();

        // @codingStandardsIgnoreStart
        $this->assertNull($record->purged_at);
        // @codingStandardsIgnoreEnd
    }

    public function testIsPurged()
    {
        $record = TestSoftDeleteTrash::save();
        $this->assertFalse($record->isPurged());

        $record->destroy();
        $record->purge();
        $this->assertTrue($record->isPurged());
    }

    public function testEnableTrash()
    {
        $record = TestSoftDeleteTrash::save();
        // @codingStandardsIgnoreStart
        $this->assertNull($record->deleted_at);
        $this->assertNull($record->purged_at);
        // @codingStandardsIgnoreEnd

        $record->destroy();

        // @codingStandardsIgnoreStart
        $this->assertNotEmpty($record->deleted_at);
        $this->assertNull($record->purged_at);
        /** @codingStandardsIgnoreEnd */
        $last = TestSoftDeleteTrash::onlyDeleted()->desc('id')->first();
        $this->assertSame($record->id, $last->id);

        $record->purge();

        // @codingStandardsIgnoreStart
        $this->assertNotEmpty($record->purged_at);
        /** @codingStandardsIgnoreEnd */
        $last = TestSoftDeleteTrash::onlyDeleted()->desc('id')->first();
        $lastId = $last ? $last->id : null;
        $this->assertNotSame($record->id, $lastId);
    }

    public function testOnlyDeleted()
    {
        $record = TestSoftDeleteTrash::save();

        $record->destroy();
        $record = TestSoftDeleteTrash::onlyDeleted()->find($record->id);
        $this->assertNotNull($record);

        $record->purge();
        $record = TestSoftDeleteTrash::onlyDeleted()->find($record->id);
        $this->assertNull($record);
    }

    public function testOnlyPurged()
    {
        $record = TestSoftDeleteTrash::save();

        $record->destroy();
        $purgedRecord = TestSoftDeleteTrash::onlyPurged()->find($record->id);
        $this->assertNull($purgedRecord);

        $record->purge();
        $record = TestSoftDeleteTrash::onlyPurged()->find($record->id);
        $this->assertNotNull($record);
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_soft_delete_trashes');
    }
}
