<?php

namespace WeiTest;

use Wei\Schema;

/**
 * Schema
 *
 * @property Schema $schema
 */
class SchemaTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        wei()->schema->db = wei()->mysqlDb;
        wei()->schema->setOption([
            'charset' => 'utf8mb4',
            'collate' => 'utf8mb4_unicode_ci',
        ]);
    }

    /**
     * 生成SQL语句
     */
    public function testCreateTable()
    {
        $sql = $this->schema->table('test')->tableComment('Test')
            ->id()
            ->int('user_id')->comment('User ID')
            ->string('name')
            ->text('description')
            ->decimal('price', 10, 2)
            ->bool('is_default')->defaults(true)
            ->tinyInt('type', 1)
            ->bigInt('big_id')
            ->char('id_card', 18)
            ->double('lat')
            ->longText('long_description')
            ->mediumText('medium_description')
            ->smallInt('small_id')
            ->date('birthday')
            ->datetime('closed_at')
            ->timestampsV1()
            ->userstampsV1()
            ->softDeletableV1()
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->index('user_id')
            ->unique('name')
            ->getSql();

        $this->assertEquals("CREATE TABLE test (
  id int unsigned NOT NULL AUTO_INCREMENT,
  user_id int unsigned NOT NULL DEFAULT 0 COMMENT 'User ID',
  name varchar(255) NOT NULL DEFAULT '',
  description text NOT NULL,
  price decimal(10, 2) unsigned NOT NULL DEFAULT 0,
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

    public function testChange()
    {
        $sql = wei()->schema->table('products')
            ->string('no', 64)->comment('商品编码')->change()
            ->string('barcode', 64)->comment('条码')->after('no')
            ->getSql();

        $this->assertEquals("ALTER TABLE products
  CHANGE COLUMN no no varchar(64) NOT NULL DEFAULT '' COMMENT '商品编码',
  ADD COLUMN barcode varchar(64) NOT NULL DEFAULT '' COMMENT '条码' AFTER no
", $sql);
    }

    public function testRename()
    {
        $this->createTestTable();

        $sql = wei()->schema->table('test_products')
            ->renameColumn('name', 'new_name')
            ->getSql();

        $this->assertEquals("ALTER TABLE test_products
  CHANGE COLUMN name new_name varchar(128) NOT NULL DEFAULT '' COMMENT 'product name'
", $sql);

        $this->dropTestTable();
    }

    public function testRenameTextColumn()
    {
        $this->createTestTable();

        $sql = wei()->schema->table('test_products')
            ->renameColumn('description', 'new_description')
            ->getSql();

        // Text column don't support default value
        $this->assertEquals("ALTER TABLE test_products
  CHANGE COLUMN description new_description text NOT NULL COMMENT 'product description'
", $sql);

        $this->dropTestTable();
    }

    public function testRenameNotExistsColumn()
    {
        $this->createTestTable();

        $this->setExpectedException('Exception', 'Column "not_exists" not found in table "test_products"');

        wei()->schema->table('test_products')
            ->renameColumn('not_exists', 'new_column')
            ->exec();
    }

    public function testDrop()
    {
        $sql = wei()->schema->table('test')
            ->dropColumn('test')
            ->getSql();

        $this->assertEquals("ALTER TABLE test
  DROP COLUMN test
", $sql);
    }

    public function testMultiCommands()
    {
        $this->createTestTable();

        $sql = wei()->schema->table('test_products')
            ->string('new_description')->comment('product detail')
            ->renameColumn('name', 'new_name')
            ->dropColumn('test')
            ->getSql();

        $this->assertEquals("ALTER TABLE test_products
  ADD COLUMN new_description varchar(255) NOT NULL DEFAULT '' COMMENT 'product detail',
  CHANGE COLUMN name new_name varchar(128) NOT NULL DEFAULT '' COMMENT 'product name',
  DROP COLUMN test
", $sql);

        wei()->schema->db->executeUpdate($sql);

        $this->dropTestTable();
    }

    protected function createTestTable()
    {
        $this->schema->dropIfExists('test_products');
        $this->schema->table('test_products')
            ->id()
            ->string('name', 128)->comment('product name')
            ->text('description')->comment('product description')
            ->string('test')
            ->exec();
    }

    protected function dropTestTable()
    {
        $this->schema->dropIfExists('test_products');
    }
}
