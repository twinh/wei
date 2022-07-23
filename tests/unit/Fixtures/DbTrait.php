<?php

declare(strict_types=1);

namespace WeiTest\Fixtures;

/**
 * @mixin \DbMixin
 */
trait DbTrait
{
    private static $tablePrefix;

    public function initFixtures()
    {
        $db = $this->db;

        static::dropTables();
        static::createTables();

        $db->insert('test_user_groups', [
            'id' => '1',
            'name' => 'vip',
        ]);

        $db->insert('test_users', [
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test',
        ]);

        $db->insert('test_users', [
            'group_id' => '1',
            'name' => 'test',
            'address' => 'test',
        ]);
    }

    protected static function createTables()
    {
        wei()->schema->table('test_users')
            ->id()
            ->int('group_id')
            ->string('name')
            ->string('address')
            ->date('birthday')
            ->date('joined_date')->defaults("'2000-01-01'")->nullable()
            ->string('signature')->defaults('default')
            ->exec();

        wei()->schema->table('test_user_groups')
            ->id()
            ->string('name')
            ->exec();
    }

    protected static function dropTables()
    {
        wei()->schema->dropIfExists(['test_users', 'test_user_groups']);
    }

    private static function setTablePrefix($tablePrefix = '')
    {
        static::$tablePrefix = wei()->db->getTablePrefix();
        wei()->db->setOption('tablePrefix', $tablePrefix);
    }

    private static function resetTablePrefix()
    {
        wei()->db->setOption('tablePrefix', static::$tablePrefix);
    }
}
