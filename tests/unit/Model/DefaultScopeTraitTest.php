<?php

declare(strict_types=1);

namespace WeiTest\Model;

use WeiTest\Model\Fixture\TestDefaultScope;
use WeiTest\TestCase;

/**
 * @internal
 */
final class DefaultScopeTraitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::dropTables();

        wei()->schema->table('test_default_scopes')
            ->id()
            ->string('name')
            ->char('type', 1)
            ->bool('active')
            ->exec();

        wei()->db->batchInsert('test_default_scopes', [
            [
                'name' => 'first',
                'type' => 'A',
                'active' => true,
            ],
            [
                'name' => 'second',
                'type' => 'B',
                'active' => true,
            ],
            [
                'name' => 'third',
                'type' => 'A',
                'active' => false,
            ],
            [
                'name' => 'fourth',
                'type' => 'B',
                'active' => false,
            ],
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        self::dropTables();
        parent::tearDownAfterClass();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_default_scopes');
    }

    public function testExecuteWithSqlPart()
    {
        $record = TestDefaultScope::select('id')->fetchAll();

        $this->assertCount(1, $record);
    }

    public function testExecuteWithoutSqlPart()
    {
        $record = TestDefaultScope::fetchAll();

        $this->assertCount(1, $record);
    }

    public function testExecuteWithWhere()
    {
        $record = TestDefaultScope::where('name', 'first')->fetchAll();

        $this->assertCount(1, $record);
    }

    public function testGetDefaultScopes()
    {
        $scopes = TestDefaultScope::getDefaultScopes();

        $this->assertEquals(['active' => true, 'typeA' => true], $scopes);
    }

    public function testUnscopedAll()
    {
        $count = TestDefaultScope::unscoped()->cnt();

        $this->assertEquals(4, $count);
    }

    public function testUnscopedOne()
    {
        $count = TestDefaultScope::unscoped('active')->cnt();

        $this->assertEquals(2, $count);
    }

    public function testUnscopeMulti()
    {
        $count = TestDefaultScope::unscoped(['active', 'typeA'])->cnt();

        $this->assertEquals(4, $count);
    }

    public function testScopeWithClosure()
    {
        $scope = TestDefaultScope::where(static function (TestDefaultScope $defaultScope) {
            $defaultScope->where('name', 'test');
        });

        $this->assertStringNotContainsString('(`active` = 1', $scope->getRawSql());

        $this->assertStringContainsString(
            "WHERE `active` = 1 AND `type` = 'A' AND (`name` = 'test')",
            $scope->getRawSql()
        );
    }
}
