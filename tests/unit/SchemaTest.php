<?php

namespace WeiTest;

use SchemaMixin;

/**
 * @mixin SchemaMixin
 * @internal
 */
final class SchemaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->wei->getConfig('mysql.db')) {
            $this->schema->db = $this->wei->mysqlDb;
        }

        $this->schema->setOption([
            'charset' => 'utf8mb4',
            'collate' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function testCreateTable()
    {
        $sql = $this->schema->table('test')->tableComment('Test')
            ->id()
            ->int('user_id')->comment('User ID')
            ->uInt('unsigned_user_id')
            ->string('name')
            ->text('description')
            ->decimal('price', 10, 2)
            ->uDecimal('unsigned_price', 10, 2)
            ->bool('is_default')->defaults(true)
            ->tinyInt('type', 1)
            ->uTinyInt('unsigned_type')
            ->mediumInt('medium_id')
            ->uMediumInt('unsigned_medium_id')
            ->bigInt('big_id')
            ->uBigInt('unsigned_big_id')
            ->char('id_card', 18)
            ->double('lat')
            ->uDouble('unsigned_lat')
            ->longText('long_description')
            ->mediumText('medium_description')
            ->smallInt('small_id')
            ->uSmallInt('unsigned_small_id')
            ->date('birthday')
            ->datetime('closed_at')
            ->json('json')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->index('user_id')
            ->unique('name')
            ->getSql();

        $this->assertSqlSame("CREATE TABLE test (
  id int unsigned NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL DEFAULT 0 COMMENT 'User ID',
  unsigned_user_id int unsigned NOT NULL DEFAULT 0,
  name varchar(255) NOT NULL DEFAULT '',
  description text NOT NULL,
  price decimal(10, 2) NOT NULL DEFAULT 0,
  unsigned_price decimal(10, 2) unsigned NOT NULL DEFAULT 0,
  is_default tinyint(1) NOT NULL DEFAULT 1,
  type tinyint(1) NOT NULL DEFAULT 0,
  unsigned_type tinyint unsigned NOT NULL DEFAULT 0,
  medium_id mediumint NOT NULL DEFAULT 0,
  unsigned_medium_id mediumint unsigned NOT NULL DEFAULT 0,
  big_id bigint NOT NULL DEFAULT 0,
  unsigned_big_id bigint unsigned NOT NULL DEFAULT 0,
  id_card char(18) NOT NULL DEFAULT '',
  lat double NOT NULL DEFAULT 0,
  unsigned_lat double unsigned NOT NULL DEFAULT 0,
  long_description longtext NOT NULL,
  medium_description mediumtext NOT NULL,
  small_id smallint NOT NULL DEFAULT 0,
  unsigned_small_id smallint unsigned NOT NULL DEFAULT 0,
  birthday date NULL DEFAULT NULL,
  closed_at datetime NULL DEFAULT NULL,
  json json NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  created_by int unsigned NOT NULL DEFAULT 0,
  updated_by int unsigned NOT NULL DEFAULT 0,
  deleted_at timestamp NULL DEFAULT NULL,
  deleted_by int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY id (id),
  KEY user_id (user_id),
  UNIQUE KEY name (name)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Test'", $sql);
    }

    public function testBigId()
    {
        $sql = $this->schema->table('test')
            ->bigId()
            ->getSql();
        $this->assertSqlSame('CREATE TABLE test (
  id bigint unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY id (id)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci', $sql);

        $sql = $this->schema->table('test')
            ->bigId('test_id')
            ->getSql();
        $this->assertSqlSame('CREATE TABLE test (
  test_id bigint unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY test_id (test_id)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci', $sql);
    }

    public function testChange()
    {
        $sql = $this->schema->table('products')
            ->string('no', 64)->comment('商品编码')->change()
            ->string('barcode', 64)->comment('条码')->after('no')
            ->getSql();

        $this->assertSqlSame("ALTER TABLE products
  CHANGE COLUMN no no varchar(64) NOT NULL DEFAULT '' COMMENT '商品编码',
  ADD COLUMN barcode varchar(64) NOT NULL DEFAULT '' COMMENT '条码' AFTER no
", $sql);
    }

    public function testRename()
    {
        $this->createTestTable();

        $sql = $this->schema->table('test_products')
            ->renameColumn('name', 'new_name')
            ->getSql();

        $this->assertSqlSame("ALTER TABLE test_products
  CHANGE COLUMN name new_name varchar(128) NOT NULL DEFAULT '' COMMENT 'product name'
", $sql);

        $this->dropTestTable();
    }

    public function testRenameTextColumn()
    {
        $this->createTestTable();

        $sql = $this->schema->table('test_products')
            ->renameColumn('description', 'new_description')
            ->getSql();

        // Text column don't support default value
        $this->assertSqlSame("ALTER TABLE test_products
  CHANGE COLUMN description new_description text NOT NULL COMMENT 'product description'
", $sql);

        $this->dropTestTable();
    }

    public function testRenameNotExistsColumn()
    {
        $this->createTestTable();

        $this->setExpectedException('Exception', 'Column "not_exists" not found in table "test_products"');

        $this->schema->table('test_products')
            ->renameColumn('not_exists', 'new_column')
            ->exec();
    }

    public function testDrop()
    {
        $sql = $this->schema->table('test')
            ->dropColumn('test')
            ->getSql();

        $this->assertSqlSame('ALTER TABLE test
  DROP COLUMN test
', $sql);
    }

    public function testMultiCommands()
    {
        $this->createTestTable();

        $sql = $this->schema->table('test_products')
            ->string('new_description')->comment('product detail')
            ->renameColumn('name', 'new_name')
            ->dropColumn('test')
            ->getSql();

        $this->assertSqlSame("ALTER TABLE test_products
  ADD COLUMN new_description varchar(255) NOT NULL DEFAULT '' COMMENT 'product detail',
  CHANGE COLUMN name new_name varchar(128) NOT NULL DEFAULT '' COMMENT 'product name',
  DROP COLUMN test
", $sql);

        $this->schema->db->executeUpdate($sql);

        $this->dropTestTable();
    }

    public function testNullable()
    {
        $sql = $this->schema->table('test_null')
            ->string('id')->nullable(true)
            ->getSql();

        $this->assertSqlSame("CREATE TABLE test_null (
  id varchar(255) NULL DEFAULT ''
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci", $sql);
    }

    public function testDefaultNullable()
    {
        $schema = $this->schema;

        $schema->setDefaultNullable(true);

        $sql = $schema->table('test')
            ->string('id')
            ->getSql();
        $this->assertSqlSame("CREATE TABLE test (
  id varchar(255) NULL DEFAULT ''
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci", $sql);

        $schema->setDefaultNullable(false);

        $sql = $schema->table('test')
            ->string('id')
            ->getSql();
        $this->assertSqlSame("CREATE TABLE test (
  id varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci", $sql);
    }

    public function testNullableAndDefault()
    {
        $sql = $this->schema->table('test')
            ->timestamp('id')
            ->getSql();

        $this->assertSqlSame('CREATE TABLE test (
  id timestamp NULL DEFAULT NULL
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci', $sql);
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

    private function assertSqlSame($expected, $actual, string $message = ''): void
    {
        $this->assertSame(
            str_replace(' TABLE ', ' TABLE ' . $this->schema->db->getTablePrefix(), $expected),
            $actual,
            $message
        );
    }
}
