<?php

namespace WidgetTest;

use Widget\Db\Record;

/**
 * @property \Widget\Db $db
 */
class RecordTest extends TestCase
{
    public function testSaveOnNoFiledChanged()
    {
        $record = $this->db->create('test', array('id' => 1), false);
        $this->assertTrue($record->save());
    }

    public function testPrimaryKey()
    {
        $record = $this->db->create('test');
        $this->assertEquals('id', $record->getPrimaryKey());

        $record->setPrimaryKey('testId');
        $this->assertEquals('testId', $record->getPrimaryKey());
    }

    public function testIsNew()
    {
        $record = $this->db->create('test', array('id' => 1), true);
        $this->assertTrue($record->isNew());

        $record = $this->db->create('test', array('id' => 1), false);
        $this->assertFalse($record->isNew());
    }
}