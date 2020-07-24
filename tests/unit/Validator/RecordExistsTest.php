<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class RecordExistsTest extends TestCase
{
    protected $inputTestOptions = [
        'table' => 'users',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $db = $this->wei->db;

        $db->query('CREATE TABLE users (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))');

        $db->insert('users', [
            'name' => 'twin',
            'address' => 'test',
        ]);

        $db->insert('users', [
            'name' => 'test',
            'address' => 'test',
        ]);
    }

    /**
     * @dataProvider dataProviderForRecordExists
     * @param mixed $input
     * @param mixed $field
     */
    public function testRecordExists($input, $field)
    {
        $this->assertTrue($this->isRecordExists($input, 'users', $field));
        $this->assertNotEmpty($this->isRecordExists->getData());
    }

    /**
     * @dataProvider dataProviderForRecordNotExists
     * @param mixed $input
     * @param mixed $field
     */
    public function testRecordNotExists($input, $field)
    {
        $this->assertFalse($this->isRecordExists($input, 'users', $field));
        $this->assertEmpty($this->isRecordExists->getData());
    }

    public function dataProviderForRecordExists()
    {
        return [
            ['1', 'id'],
            ['2', 'id'],
            ['twin', 'name'],
            ['test', 'name'],
            ['test', 'address'],
        ];
    }

    public function dataProviderForRecordNotExists()
    {
        return [
            ['3', 'id'],
            ['4', 'id'],
            ['twin', 'address'],
            ['test2', 'name'],
            ['test2', 'address'],
        ];
    }
}
