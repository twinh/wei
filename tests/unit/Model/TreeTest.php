<?php

namespace WeiTest\Model;

use InvalidArgumentException;
use WeiTest\Model\Fixture\TestTree;
use WeiTest\TestCase;

/**
 * @mixin \DbPropMixin
 * @internal
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
final class TreeTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::dropTables();

        wei()->schema->table('test_trees')
            ->bigId()
            ->uBigInt('parent_id')
            ->uSmallInt('level')
            ->string('path')
            ->string('name')
            ->exec();

        $node = TestTree::new()->save(['name' => 'root']);

        $node1 = $node->saveNode(['name' => '1']);
        $node->saveNode(['name' => '2']);

        $node11 = $node1->saveNode(['name' => '1-1']);
        $node11->saveNode(['name' => '1-1-1']);
    }

    public static function tearDownAfterClass(): void
    {
        static::dropTables();
        parent::tearDownAfterClass();
    }

    protected function tearDown(): void
    {
        TestTree::resetBoot();
        parent::tearDown();
    }

    public static function dropTables()
    {
        wei()->schema->dropIfExists('test_trees');
    }

    public function testParent()
    {
        $node = TestTree::new()->findBy('name', 'root');
        $child = $node->children()->first();
        $this->assertSame($node->id, $child->parent->id);
    }

    public function testChildren()
    {
        $node = TestTree::new()->findBy('name', 'root');
        $children = $node->children;

        $this->assertCount(2, $children);
        $this->assertSame('1', $children[0]['name']);
        $this->assertSame('2', $children[1]['name']);
    }

    public function testModelLoadRoot()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode();
        $node11 = $node1->saveNode();

        $root = $node11->root;
        $this->assertSame($root->id, $node->id);
    }

    public function testCollLoadRoot()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $anotherRoot = TestTree::new()->save();
        $node2 = $anotherRoot->saveNode(['name' => '2']);
        $node22 = $node2->saveNode(['name' => '2-1']);

        $coll = TestTree::newColl([
            $node11,
            $node22,
        ]);
        $coll->load('root');

        $this->assertSame($node->id, $node11->root->id);

        $this->assertSame($anotherRoot->id, $node22->root->id);

        // Load 2 roots in one query
        $queries = $this->db->getQueries();
        $this->assertSqlSame('SELECT * FROM `test_trees` WHERE `path` IN (?, ?)', end($queries));
    }

    public function testModelLoadAncestors()
    {
        $node = TestTree::new()->save(['name' => 'root']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $ancestors = $node11->ancestors;

        $this->assertCount(2, $ancestors);
        $this->assertSame($node->id, $ancestors[0]->id);
        $this->assertSame($node1->id, $ancestors[1]->id);
    }

    public function testCollLoadAncestors()
    {
        $node = TestTree::new()->save(['name' => 'root']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $node2 = $node->saveNode(['name' => '2']);
        $node22 = $node2->saveNode(['name' => '2-1']);

        $coll = TestTree::newColl([
            $node11,
            $node22,
        ]);
        $coll->load('ancestors');

        $this->assertCount(2, $node11->ancestors);
        $this->assertSame($node->id, $node11->ancestors[0]->id);
        $this->assertSame($node1->id, $node11->ancestors[1]->id);

        $this->assertCount(2, $node22->ancestors);
        $this->assertSame($node->id, $node22->ancestors[0]->id);
        $this->assertSame($node2->id, $node22->ancestors[1]->id);

        // Load 3 ancestors in one query
        $queries = $this->db->getQueries();
        $this->assertSqlSame('SELECT * FROM `test_trees` WHERE `path` IN (?, ?, ?) ORDER BY `path` ASC', end($queries));
    }

    public function testModelLoadDescendants()
    {
        $parent = TestTree::new()->save();

        $child1 = TestTree::new()->save([
            'parent_id' => $parent->id,
            'name' => 'c1',
        ]);
        $child2 = TestTree::new()->save([
            'parent_id' => $parent->id,
            'name' => 'c2',
        ]);

        $grandson1 = TestTree::new()->save([
            'parent_id' => $child1->id,
            'name' => 'g1',
        ]);
        $grandson11 = TestTree::new()->save([
            'parent_id' => $child1->id,
            'name' => 'g11',
        ]);

        $descendants = $parent->descendants()->asc('id')->all();
        $this->assertCount(4, $descendants);

        $this->assertSame($child1->id, $descendants[0]->id);
        $this->assertSame($child2->id, $descendants[1]->id);
        $this->assertSame($grandson1->id, $descendants[2]->id);
        $this->assertSame($grandson11->id, $descendants[3]->id);

        $child2Descendants = $child2->descendants;
        $this->assertCount(0, $child2Descendants);
    }

    public function testCollLoadDescendants()
    {
        $root = TestTree::new()->save();

        $node1 = $root->saveNode();
        $node11 = $node1->saveNode();
        $node111 = $node11->saveNode();

        $node2 = $root->saveNode();
        $node21 = $node2->saveNode();
        $node211 = $node21->saveNode();

        $coll = TestTree::newColl([
            $node1,
            $node2,
        ]);
        $coll->load('descendants');

        $this->assertCount(2, $node1->descendants);
        $this->assertSame($node11->id, $node1->descendants[0]->id);
        $this->assertSame($node111->id, $node1->descendants[1]->id);

        $this->assertCount(2, $node2->descendants);
        $this->assertSame($node21->id, $node2->descendants[0]->id);
        $this->assertSame($node211->id, $node2->descendants[1]->id);

        // Load 2 ancestors in one query
        $queries = $this->db->getQueries();
        $this->assertSqlSame('SELECT * FROM `test_trees` WHERE (`path` LIKE ? OR `path` LIKE ?)', end($queries));

        $this->assertCount(4, $coll->descendants);
    }

    public function testSiblings()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode(['name' => '1']);
        $node2 = $node->saveNode(['name' => '2']);

        $siblings = $node1->siblings()->all();
        $this->assertCount(1, $siblings);
        $this->assertSame($node2->id, $siblings[0]->id);
    }

    public function testGetSelfAndDescendantsIds()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode();
        $node2 = $node->saveNode();

        $node11 = $node1->saveNode();

        $this->assertSame([$node->id, $node1->id, $node2->id, $node11->id], $node->getSelfAndDescendantsIds());
        $this->assertSame([$node1->id, $node11->id], $node1->getSelfAndDescendantsIds());
    }

    public function testCreateChildNode()
    {
        $node = TestTree::new()->save();
        $node1 = TestTree::new();
        $node1->parent_id = $node->id;
        $node1->save();

        $this->assertSame(2, $node1->level);
        $this->assertStringStartsWith($node->path, $node->path);
    }

    public function testMoveNodeToSibling()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);
        $node111 = $node11->saveNode(['name' => '1-1-1']);
        $node2 = $node->saveNode(['name' => '2']);
        $this->assertNode($node, <<<'EOF'
-1
--1-1
---1-1-1
-2
EOF
        );

        $node11->set('parent_id', $node2->id);
        $node11->save();

        $node->removeRelationValue('children');
        $this->assertNode($node, <<<'EOF'
-1
-2
--1-1
---1-1-1
EOF
        );

        $descendants = $node2->descendants()->all()->indexBy('id');
        $this->assertArrayHasKey($node111->id, $descendants);
    }

    public function testMoveNodeToChild()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode();
        $node11 = $node1->saveNode();

        $this->expectExceptionObject(new InvalidArgumentException('Node can\'t move to child'));

        $node->save(['parent_id' => $node11->id]);
    }

    public function testMoveNodeToSelf()
    {
        $node = TestTree::new()->first();

        $this->expectExceptionObject(new InvalidArgumentException('Node can\'t move to self'));

        $node->save(['parent_id' => $node->id]);
    }

    public function testMoveNodeToRoot()
    {
        $node = TestTree::new()->save(['name' => '0']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $node1->save(['parent_id' => null]);

        $this->assertNode($node1, <<<'EOF'
-1-1
EOF
        );
        $this->assertSame(1, $node1->level);
        $this->assertSame('', $node1->parent_id);

        $node11->reload();
        $this->assertSame(2, $node11->level);

        $ancestors = $node->ancestors;
        $this->assertCount(0, $ancestors);
    }

    public function testMoveNodeDown()
    {
        $node = TestTree::new()->save(['name' => '0']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);
        $node111 = $node11->saveNode(['name' => '1-1-1']);

        $node2 = $node->saveNode(['name' => '2']);
        $this->assertSame(<<<'EOF'
-1
--1-1
---1-1-1
-2
EOF
            , $this->treeToString($node));

        $node1->save(['parent_id' => $node2->id]);

        $node->removeRelationValue('children');
        $this->assertSame(<<<'EOF'
-2
--1
---1-1
----1-1-1
EOF
            , $this->treeToString($node));

        $this->assertSame(2, $node2->level);
        $this->assertSame(3, $node1->level);

        $node11->reload();
        $this->assertSame(4, $node11->level);

        $node111->reload();
        $this->assertSame(5, $node111->level);
    }

    public function testDestroy()
    {
        $node = TestTree::new()->save(['name' => '0']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $node->destroy();

        $queries = $this->db->getQueries();
        $queries = array_slice($queries, -4);

        // Delete current
        $this->assertSqlSame('DELETE FROM test_trees WHERE id = ?', $queries[0]);

        // Get descendants
        $this->assertSqlSame('SELECT * FROM `test_trees` WHERE (`path` LIKE ?)', $queries[1]);

        // Delete $node1
        $this->assertSqlSame('DELETE FROM test_trees WHERE id = ?', $queries[2]);

        // Delete $node11
        $this->assertSqlSame('DELETE FROM test_trees WHERE id = ?', $queries[3]);

        $this->assertNull(TestTree::new()->find($node1->id));
        $this->assertNull(TestTree::new()->find($node11->id));
    }

    public function testIsAncestorOf()
    {
        $node = TestTree::new()->save(['name' => '0']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $node2 = $node->saveNode(['name' => '2']);

        $this->assertTrue($node->isAncestorOf($node1));
        $this->assertTrue($node->isAncestorOf($node11));
        $this->assertFalse($node2->isAncestorOf($node1));
    }

    public function testIsAncestorOfNewNode()
    {
        $node = TestTree::new()->first();
        $node2 = TestTree::new();
        $this->assertFalse($node2->isAncestorOf($node));
    }

    public function testIsDescendantOf()
    {
        $node = TestTree::new()->save(['name' => '0']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $node2 = $node->saveNode(['name' => '2']);

        $this->assertTrue($node1->isDescendantOf($node));
        $this->assertTrue($node11->isDescendantOf($node));
        $this->assertFalse($node2->isDescendantOf($node1));
    }

    public function testIsDescendantOfNewNode()
    {
        $node = TestTree::new();
        $node2 = TestTree::new();
        $this->assertFalse($node2->isDescendantOf($node));
    }

    public function testToTree()
    {
        $node = TestTree::new()->save(['name' => '0']);
        $node1 = $node->saveNode(['name' => '1']);
        $node11 = $node1->saveNode(['name' => '1-1']);

        $coll = TestTree::newColl([
            $node11,
            $node1,
            $node,
        ]);

        $tree = $coll->toTree();
        $this->assertCount(1, $tree);

        $this->assertSame($tree[0]->children[0], $node1);
        $this->assertSame($tree[0]->children[0]->children[0], $node11);
    }

    public function testLoadTree()
    {
        $node = TestTree::new()->findBy('name', 'root');
        $node->loadTree();

        $this->assertNode($node, <<<'EOF'
-1
--1-1
---1-1-1
-2
EOF
        );

        // dont load children
        $queries = $this->db->getQueries();
        $this->assertSqlSame('SELECT * FROM `test_trees` WHERE (`path` LIKE ?)', end($queries));
    }

    public function testUpdateTree()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode();

        $node1->save(['level' => 1, 'path' => '']);
        $this->assertFalse($node1->isDescendantOf($node));

        $node->updateTree();
        $node1->reload();
        $this->assertSame(2, $node1->level);
        $this->assertTrue($node1->isDescendantOf($node));
    }

    public function testDestroyAndAddNotGenerateSamePath()
    {
        $node = TestTree::new()->save();
        $node1 = $node->saveNode(['name' => '1']);
        $node2 = $node->saveNode(['name' => '2']);

        $node1->destroy();
        $node3 = $node->saveNode(['name' => '3']);

        $this->assertNotSame($node2->path, $node3->path);
    }

    public function testPathIsHidden()
    {
        $node = TestTree::new()->toArray();
        $this->assertArrayNotHasKey('path', $node);
    }

    protected function treeToString($node): string
    {
        return rtrim($this->traverse($node->children));
    }

    protected function traverse($nodes): string
    {
        $result = '';
        foreach ($nodes as $node) {
            $result .= str_repeat('-', $node->level - 1) . $node->name . "\n";
            $result .= $this->traverse($node->children);
        }
        return $result;
    }

    protected function assertSqlSame($expected, $actual, string $message = '')
    {
        $this->assertSame($expected, str_replace($this->db->getTablePrefix(), '', $actual), $message);
    }

    protected function assertNode($node, $string)
    {
        $this->assertSame($string, $this->treeToString($node));
    }
}
