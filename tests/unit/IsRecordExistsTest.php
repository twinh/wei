<?php

namespace WeiTest;

/**
 * @internal
 * @mixin \SchemaMixin
 */
final class IsRecordExistsTest extends BaseValidatorTestCase
{
    protected $inputTestOptions = [
        'table' => 'users',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $db = $this->wei->db;

        $this->schema->table('users')
            ->id()
            ->string('name')
            ->string('address')
            ->exec();

        $db->insert('users', [
            'name' => 'twin',
            'address' => 'test',
        ]);

        $db->insert('users', [
            'name' => 'test',
            'address' => 'test',
        ]);
    }

    protected function tearDown(): void
    {
        $this->schema->dropIfExists('users');
        parent::tearDown();
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
