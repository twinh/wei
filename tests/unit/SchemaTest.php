<?php

namespace WeiTest;

/**
 * Schema
 */
class SchemaTest extends TestCase
{
    /**
     * 生成SQL语句
     */
    public function testGetSql()
    {
        $sql = wei()->schema->table('test')->tableComment('Test')
            ->id()
            ->int('user_id')->comment('User ID')
            ->string('name')
            ->text('description')
            ->decimal('price', 10, 2)
            ->bool('is_default')->defaults(true)
            ->tinyInt('type', 1)
            ->bigInt('big_id')
            ->char('id_card', 18)
            ->double('lat', 10, 6)
            ->longText('long_description')
            ->mediumText('medium_description')
            ->smallInt('small_id')
            ->date('birthday')
            ->datetime('closed_at')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->timestampsV2()
            ->userstampsV2()
            ->softDeletableV2()
            ->index('user_id')
            ->unique('name')
            ->getSql();

        $this->assertEquals("CREATE TABLE test (
  id int unsigned NOT NULL AUTO_INCREMENT,
  user_id int unsigned NOT NULL DEFAULT 0 COMMENT 'User ID',
  name varchar(255) NOT NULL DEFAULT '',
  description text NOT NULL,
  price decimal(10, 2) NOT NULL DEFAULT 0,
  is_default tinyint(1) unsigned NOT NULL DEFAULT 1,
  type tinyint(1) unsigned NOT NULL DEFAULT 0,
  big_id bigint unsigned NOT NULL DEFAULT 0,
  id_card char(18) NOT NULL DEFAULT '',
  lat double unsigned NOT NULL DEFAULT 0,
  long_description longtext NOT NULL,
  medium_description mediumtext NOT NULL,
  small_id smallint unsigned NOT NULL DEFAULT 0,
  birthday date NOT NULL DEFAULT '0000-00-00',
  closed_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  createTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updateTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  createUser int unsigned NOT NULL DEFAULT 0,
  updateUser int unsigned NOT NULL DEFAULT 0,
  deleteTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  deleteUser int unsigned NOT NULL DEFAULT 0,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int unsigned NOT NULL DEFAULT 0,
  updated_by int unsigned NOT NULL DEFAULT 0,
  deleted_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  deleted_by int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY id (id),
  KEY user_id (user_id),
  UNIQUE KEY name (name)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Test'", $sql);
    }
}
