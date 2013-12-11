<?php

namespace WeiTest\Validator;

class RecordExistsTest extends TestCase
{
    protected $inputTestOptions = array(
        'table' => 'users'
    );

    public function setUp()
    {
        parent::setUp();

        $db = $this->wei->db;

        $db->query("CREATE TABLE users (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))");

        $db->insert('users', array(
            'name' => 'twin',
            'address' => 'test'
        ));

        $db->insert('users', array(
            'name' => 'test',
            'address' => 'test'
        ));
    }

    /**
     * @dataProvider dataProviderForRecordExists
     */
    public function testRecordExists($input, $field)
    {
        $this->assertTrue($this->isRecordExists($input, 'users', $field));
        $this->assertNotEmpty($this->isRecordExists->getData());
    }

    /**
     * @dataProvider dataProviderForRecordNotExists
     */
    public function testRecordNotExists($input, $field)
    {
        $this->assertFalse($this->isRecordExists($input, 'users', $field));
        $this->assertEmpty($this->isRecordExists->getData());
    }

    public function dataProviderForRecordExists()
    {
        return array(
            array('1', 'id'),
            array('2', 'id'),
            array('twin', 'name'),
            array('test', 'name'),
            array('test', 'address')
        );
    }

    public function dataProviderForRecordNotExists()
    {
        return array(
            array('3', 'id'),
            array('4', 'id'),
            array('twin', 'address'),
            array('test2', 'name'),
            array('test2', 'address')
        );
    }
}
