<?php

namespace WeiTest;

use PDO;
use RuntimeException;

/**
 * @property \Wei\Db db
 * @method \Wei\Record db($table = null)
 * @internal
 */
class DbTest extends TestCase
{
    public function initFixtures()
    {
        $db = $this->db;

        $db->setTablePrefix('prefix_');

        $this->dropTable();
        $this->createTable();

        $db->insert('member_group', [
            'id' => '1',
            'name' => 'vip',
        ]);

        $db->insert('member', [
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test',
        ]);

        $db->insert('member', [
            'group_id' => '1',
            'name' => 'test',
            'address' => 'test',
        ]);

        $db->insert('post', [
            'member_id' => '1',
            'name' => 'my first post',
        ]);

        $db->insert('post', [
            'member_id' => '1',
            'name' => 'my second post',
        ]);

        $db->insert('tag', [
            'id' => '1',
            'name' => 'database',
        ]);

        $db->insert('tag', [
            'id' => '2',
            'name' => 'PHP',
        ]);

        $db->insert('post_tag', [
            'post_id' => '1',
            'tag_id' => '1',
        ]);

        $db->insert('post_tag', [
            'post_id' => '1',
            'tag_id' => '2',
        ]);

        $db->insert('post_tag', [
            'post_id' => '2',
            'tag_id' => '1',
        ]);
    }

    public function testIsConnected()
    {
        $db = $this->db;

        $db->connect();
        $this->assertTrue($db->isConnected());

        $db->close();
        $this->assertFalse($db->isConnected());
    }

    public function testGetRecord()
    {
        $this->initFixtures();
        $this->assertInstanceOf('\Wei\Record', $this->db->init('member'));
    }

    public function testRelation()
    {
        $this->initFixtures();

        $db = $this->db;

        $db->setOption('recordNamespace', 'WeiTest\Db');

        /** @var $member \WeiTest\Db\Member */
        $member = $this->db('member')->find('1');

        $this->assertInstanceOf('\Wei\Record', $member);

        $this->assertEquals('1', $member['id']);
        $this->assertEquals('twin', $member['name']);
        $this->assertEquals('test', $member['address']);
        $this->assertEquals('1', $member['group_id']);

        // Relation one-to-one
        $post = $member->getPost();

        $this->assertInstanceOf('\Wei\Record', $post);

        $this->assertEquals('1', $post['id']);
        $this->assertEquals('my first post', $post['name']);
        $this->assertEquals('1', $post['member_id']);

        // Relation belong-to
        $group = $member->getGroup();

        $this->assertInstanceOf('\Wei\Record', $group);

        $this->assertEquals('1', $group['id']);
        $this->assertEquals('vip', $group['name']);

        // Relation one-to-many
        $posts = $member->getPosts();

        $this->assertInstanceOf('\Wei\Record', $posts);

        $firstPost = $posts[0];
        $this->assertInstanceOf('\Wei\Record', $firstPost);

        $this->assertEquals('1', $firstPost['id']);
        $this->assertEquals('my first post', $firstPost['name']);
        $this->assertEquals('1', $firstPost['member_id']);
    }

    public function testSet()
    {
        $this->initFixtures();

        $member = $this->db('member')->find('1');

        $this->assertEquals('1', $member['id']);

        $member['id'] = 2;

        $this->assertEquals('2', $member['id']);
    }

    public function testGetRelation()
    {
        $this->initFixtures();

        $member = $this->db('member')->find('1');

        $post = $member->post = $this->db->find('post', ['member_id' => $member['id']]);

        $this->assertInstanceOf('\Wei\Record', $post);

        $this->assertEquals('1', $post['id']);
        $this->assertEquals('my first post', $post['name']);
        $this->assertEquals('1', $post['member_id']);
    }

    public function testUpdate()
    {
        $this->initFixtures();

        $this->db->update('member', ['name' => 'hello'], ['id' => '1']);

        $member = $this->db->find('member', '1');

        $this->assertEquals('hello', $member['name']);
    }

    public function testDelete()
    {
        $this->initFixtures();

        $this->db->delete('member', ['id' => '1']);

        $member = $this->db->find('member', 1);

        $this->assertFalse($member);
    }

    public function testFind()
    {
        $this->initFixtures();

        $member = $this->db->find('member', '1');

        $this->assertEquals('1', $member['id']);
    }

    public function testFindOrInit()
    {
        $this->initFixtures();

        $member = $this->db->find('member', '3');
        $this->assertFalse($member);

        // Not found and create new object
        $member = $this->db->findOrInit('member', '3', [
            'name' => 'name',
        ]);
        $this->assertEquals('name', $member['name']);
        $this->assertEquals('3', $member['id']);

        // Found
        $member = $this->db->findOrInit('member', '2');

        $this->assertEquals('2', $member['id']);

        $member = $this->db->findOrInit('member', '3', [
            'id' => '1', // Would be overwrite
            'name' => 'twin',
        ]);

        $this->assertNotEquals('1', $member['id']);
        $this->assertEquals('3', $member['id']);

        $member = $this->db->findOrInit('member', [
            'group_id' => '1',
            'name' => 'twin2',
        ]);

        $this->assertEquals('1', $member['group_id']);
        $this->assertEquals('twin2', $member['name']);
    }

    public function testFindOrInitAndStatusIsNew()
    {
        $this->initFixtures();

        $member = $this->db->findOrInit('member', '3', [
            'name' => 'name',
        ]);
        $this->assertTrue($member->isNew());
        $this->assertFalse($member->isDestroyed());
    }

    public function testFindOrInitWithSameFields()
    {
        $this->initFixtures();

        // The init data may from request, contains key like id, name
        $member = $this->db('member')->findOrInit(['id' => 3, 'name' => 'tom'], ['name' => 'name', 'id' => '5']);

        $this->assertEquals(3, $member['id']);
        $this->assertEquals('tom', $member['name']);
    }

    public function testRecordSave()
    {
        $this->initFixtures();

        $db = $this->db;

        // Existing member
        $member = $db('member')->find(1);
        $member->address = 'address';
        $result = $member->save();

        $this->assertSame($result, $member);
        $this->assertEquals('1', $member['id']);

        // New member save with data
        $member = $db->init('member');
        $this->assertTrue($member->isNew());
        $member->fromArray([
            'group_id' => '1',
            'name' => 'save',
            'address' => 'save',
        ]);
        $result = $member->save();

        $this->assertFalse($member->isNew());
        $this->assertSame($result, $member);
        $this->assertEquals('3', $member['id']);
        $this->assertEquals('save', $member['name']);

        // Save again
        $member->address = 'address3';
        $result = $member->save();
        $this->assertSame($result, $member);
        $this->assertEquals('3', $member['id']);
    }

    public function testRecordIsLoaded()
    {
        $this->initFixtures();

        $member = $this->db('member');

        $this->assertFalse($member->isLoaded());

        $member->find('1');

        $this->assertTrue($member->isLoaded());
    }

    public function testSelect()
    {
        $this->initFixtures();

        $data = $this->db->select('member', 1);
        $this->assertEquals('twin', $data['name']);

        // Empty array as conditions
        $data = $this->db->select('member', []);
        $this->assertArrayHasKey('name', $data);
    }

    public function testSelectWithField()
    {
        $this->initFixtures();

        $data = $this->db->select('member', 1, 'id, name');

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayNotHasKey('group_id', $data);
    }

    public function testSelectAll()
    {
        $this->initFixtures();

        $data = $this->db->selectAll('member', ['name' => 'twin']);

        $this->assertCount(1, $data);

        $data = $this->db->selectAll('member');

        $this->assertCount(2, $data);
    }

    public function testFetch()
    {
        $this->initFixtures();

        $data = $this->db->fetch('SELECT * FROM prefix_member WHERE name = ?', 'twin');
        $this->assertIsArray($data);
        $this->assertEquals('twin', $data['name']);
        $this->assertEquals('SELECT * FROM prefix_member WHERE name = ?', $this->db->getLastQuery());

        $data = $this->db->fetch('SELECT * FROM prefix_member WHERE name = ?', 'notFound');
        $this->assertFalse($data);
        $this->assertEquals('SELECT * FROM prefix_member WHERE name = ?', $this->db->getLastQuery());

        $data = $this->db->fetch('SELECT * FROM prefix_member WHERE name = :name', ['name' => 'twin']);
        $this->assertIsArray($data);
        $this->assertEquals('twin', $data['name']);

        $data = $this->db->fetch('SELECT * FROM prefix_member WHERE name = :name', [':name' => 'twin']);
        $this->assertIsArray($data);
        $this->assertEquals('twin', $data['name']);
    }

    public function testFetchAll()
    {
        $this->initFixtures();

        $data = $this->db->fetchAll('SELECT * FROM prefix_member WHERE group_id = ?', '1');

        $this->assertIsArray($data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testQueryFetch()
    {
        $this->initFixtures();

        $data = $this->db('member')->where('id = 1')->fetch();
        $this->assertIsArray($data);
        $this->assertEquals('1', $data['id']);
    }

    public function testQueryFetchAll()
    {
        $this->initFixtures();

        $data = $this->db('member')->fetchAll();

        $this->assertIsArray($data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testGetRecordClass()
    {
        $db = $this->db;

        $db->setOption('recordNamespace', 'WeiTest\Db');

        $this->assertEquals('WeiTest\Db\Member', $db->getRecordClass('member'));
        $this->assertEquals('WeiTest\Db\Member', $db->getRecordClass('member'));
        $this->assertEquals('WeiTest\Db\MemberGroup', $db->getRecordClass('member_group'));
        $this->assertEquals('WeiTest\Db\MemberGroup', $db->getRecordClass('memberGroup'));
        $this->assertEquals('WeiTest\Db\MemberGroup', $db->getRecordClass('member_Group'));
    }

    /**
     * @link http://edgeguides.rubyonrails.org/active_record_querying.html#conditions
     */
    public function testQuery()
    {
        $this->initFixtures();

        // Pure string conditions
        $query = $this->db('member')->where("name = 'twin'");
        $member = $query->find();

        $this->assertEquals("SELECT * FROM prefix_member WHERE name = 'twin' LIMIT 1", $query->getSql());
        $this->assertEquals('twin', $member['name']);

        // ? conditions
        $query = $this->db('member')->where('name = ?', 'twin');
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member WHERE name = ? LIMIT 1', $query->getSql());
        $this->assertEquals('twin', $member['name']);

        $query = $this->db('member')->where('group_id = ? AND name = ?', ['1', 'twin']);
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member WHERE group_id = ? AND name = ? LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['group_id']);
        $this->assertEquals('twin', $member['name']);

        // : conditions
        $query = $this->db('member')->where('group_id = :groupId AND name = :name', [
            'groupId' => '1',
            'name' => 'twin',
        ]);
        $member = $query->find();

        $this->assertEquals(
            'SELECT * FROM prefix_member WHERE group_id = :groupId AND name = :name LIMIT 1',
            $query->getSql()
        );
        $this->assertEquals('1', $member['group_id']);
        $this->assertEquals('twin', $member['name']);

        $query = $this->db('member')->where('group_id = :groupId AND name = :name', [
            ':groupId' => '1',
            ':name' => 'twin',
        ]);
        $member = $query->find();

        $this->assertEquals(
            'SELECT * FROM prefix_member WHERE group_id = :groupId AND name = :name LIMIT 1',
            $query->getSql()
        );
        $this->assertEquals('1', $member['group_id']);
        $this->assertEquals('twin', $member['name']);

        // Range conditions
        $query = $this->db('member')->where('group_id BETWEEN ? AND ?', ['1', '2']);
        $this->assertEquals('SELECT * FROM prefix_member WHERE group_id BETWEEN ? AND ?', $query->getSql());

        $member = $query->find();
        $this->assertGreaterThanOrEqual(1, $member['group_id']);
        $this->assertLessThanOrEqual(2, $member['group_id']);

        // Subset conditions
        $query = $this->db('member')->where(['group_id' => ['1', '2']]);
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member WHERE group_id IN (?, ?) LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['group_id']);

        $query = $this->db('member')->where([
            'id' => '1',
            'group_id' => ['1', '2'],
        ]);
        $member = $query->find();

        $this->assertEquals(
            'SELECT * FROM prefix_member WHERE id = ? AND group_id IN (?, ?) LIMIT 1',
            $query->getSql()
        );
        $this->assertEquals('1', $member['id']);

        // Overwrite where
        $query = $this
            ->db('member')
            ->where('id = :id')
            ->where('group_id = :groupId')
            ->setParameter('groupId', 1);
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member WHERE group_id = :groupId LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['group_id']);

        // Where with empty content
        $query = $this->db('member')->where(false);
        $this->assertEquals('SELECT * FROM prefix_member', $query->getSql());

        // Order
        $query = $this->db('member')->orderBy('id', 'ASC');
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member ORDER BY id ASC LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['id']);

        // Add order
        $query = $this->db('member')->orderBy('id', 'ASC')->addOrderBy('group_id', 'ASC');
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member ORDER BY id ASC, group_id ASC LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['id']);

        // Select
        $query = $this->db('member')->select('id, group_id');
        $member = $query->fetch();

        $this->assertEquals('SELECT id, group_id FROM prefix_member LIMIT 1', $query->getSql());
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);

        // Add select
        $query = $this->db('member')->select('id')->addSelect('group_id');
        $member = $query->fetch();

        $this->assertEquals('SELECT id, group_id FROM prefix_member LIMIT 1', $query->getSql());
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);

        // Distinct
        $query = $this->db('member')->select('DISTINCT group_id');
        $member = $query->find();

        $this->assertEquals('SELECT DISTINCT group_id FROM prefix_member LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['group_id']);

        // Limit
        $query = $this->db('member')->limit(2);
        $member = $query->findAll();

        $this->assertEquals('SELECT * FROM prefix_member LIMIT 2', $query->getSql());
        $this->assertCount(2, $member);

        // Offset
        $query = $this->db('member')->limit(1)->offset(1);
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member LIMIT 1 OFFSET 1', $query->getSql());
        $this->assertEquals(2, $member['id']);

        // Page
        $query = $this->db('member')->page(3);
        $this->assertEquals('SELECT * FROM prefix_member LIMIT 10 OFFSET 20', $query->getSql());

        // Mixed limit and page
        $query = $this->db('member')->limit(3)->page(3);
        $this->assertEquals('SELECT * FROM prefix_member LIMIT 3 OFFSET 6', $query->getSql());

        // Group by
        $query = $this->db('member')->groupBy('id, group_id');
        $member = $query->find();

        $this->assertEquals('SELECT * FROM prefix_member GROUP BY id, group_id LIMIT 1', $query->getSql());
        $this->assertEquals('1', $member['group_id']);

        // Having
        $query = $this->db('member')->groupBy('id, group_id')->having('group_id >= ?', '1');
        $member = $query->find();

        $this->assertEquals(
            'SELECT * FROM prefix_member GROUP BY id, group_id HAVING group_id >= ? LIMIT 1',
            $query->getSql()
        );
        $this->assertEquals('1', $member['group_id']);

        // Join
        $query = $this
            ->db('member')
            ->select('prefix_member.*, prefix_member_group.name AS group_name')
            ->leftJoin('prefix_member_group', 'prefix_member_group.id = prefix_member.group_id');
        $member = $query->fetch();

        $this->assertEquals(implode(' ', [
            'SELECT prefix_member.*, prefix_member_group.name AS group_name',
            'FROM prefix_member',
            'LEFT JOIN prefix_member_group ON prefix_member_group.id = prefix_member.group_id LIMIT 1',
        ]), $query->getSql());
        $this->assertArrayHasKey('group_name', $member);

        // Join with table alias
        $query = $this
            ->db('member u')
            ->rightJoin('prefix_member_group g', 'g.id = u.group_id');

        $this->assertEquals(
            'SELECT * FROM prefix_member u RIGHT JOIN prefix_member_group g ON g.id = u.group_id',
            $query->getSql()
        );
    }

    public function testIndexBy()
    {
        $this->initFixtures();

        $members = $this->db('member')
            ->indexBy('name')
            ->fetchAll();

        $this->assertArrayHasKey('twin', $members);
        $this->assertArrayHasKey('test', $members);

        $members = $this->db('member')
            ->indexBy('name')
            ->findAll();

        $this->assertInstanceOf('\Wei\Record', $members['twin']);
        $this->assertInstanceOf('\Wei\Record', $members['test']);

        $members = $members->toArray();

        $this->assertArrayHasKey('twin', $members);
        $this->assertArrayHasKey('test', $members);
    }

    public function testIndexByMoreThanOneTime()
    {
        $this->initFixtures();

        $members = $this->db('member')
            ->indexBy('id')
            ->findAll();

        $this->assertArrayHasKey(1, $members);

        $members->indexBy('name');
        $this->assertArrayHasKey('twin', $members);

        $members->indexBy('id');
        $this->assertArrayHasKey(1, $members);
    }

    public function testFixUndefinedOffset0WhenFetchEmptyData()
    {
        $this->initFixtures();

        $emptyMembers = $this->db('member')->where(['group_id' => '3'])->indexBy('id')->fetchAll();
        $this->assertEmpty($emptyMembers);
    }

    public function testRealTimeIndexBy()
    {
        $this->initFixtures();

        $members = $this->db('member')->findAll();

        $members = $members->indexBy('name')->toArray();

        $this->assertArrayHasKey('twin', $members);
        $this->assertArrayHasKey('test', $members);
    }

    public function testQueryUpdate()
    {
        $this->initFixtures();

        $member = $this->db('member')->where('id = 1');
        $result = $member->update("name = 'twin2'");

        $this->assertGreaterThan(0, $result);
        $this->assertEquals("UPDATE prefix_member SET name = 'twin2' WHERE id = 1", $member->getSql());

        $member = $this->db->find('member', 1);
        $this->assertEquals(1, $result);
        $this->assertEquals('twin2', $member['name']);
    }

    public function testBindValue()
    {
        $this->initFixtures();

        // Not array parameter
        $member = $this->db->fetch('SELECT * FROM prefix_member WHERE id = ?', 1, PDO::PARAM_INT);

        $this->assertEquals('1', $member['id']);

        // Array parameter
        $member = $this->db->fetch('SELECT * FROM prefix_member WHERE id = ?', [1], [PDO::PARAM_INT]);

        $this->assertEquals('1', $member['id']);

        $member = $this->db->fetch('SELECT * FROM prefix_member WHERE id = ? AND group_id = ?', [1, 1], [
            PDO::PARAM_INT, // (no parameter type for second placeholder)
        ]);

        $this->assertEquals('1', $member['id']);
        $this->assertEquals('1', $member['group_id']);

        // Name parameter
        $member = $this->db->fetch('SELECT * FROM prefix_member WHERE id = :id', [
            'id' => 1,
        ], [
            'id' => PDO::PARAM_INT,
        ]);

        $this->assertEquals('1', $member['id']);

        // Name parameter with colon
        $member = $this->db->fetch('SELECT * FROM prefix_member WHERE id = :id', [
            'id' => 1,
        ], [
            ':id' => PDO::PARAM_INT,
        ]);

        $this->assertEquals('1', $member['id']);

        $member = $this->db->fetch('SELECT * FROM prefix_member WHERE id = :id', [
            'id' => '1',
        ]);

        $this->assertEquals('1', $member['id']);
    }

    public function testFetchColumn()
    {
        $this->initFixtures();

        $count = $this->db->fetchColumn('SELECT COUNT(id) FROM prefix_member');
        $this->assertEquals(2, $count);
    }

    public function testRecordNamespace()
    {
        $this->initFixtures();

        $this->db->setOption('recordNamespace', 'WeiTest\Db');

        $member = $this->db->find('member', 1);

        $this->assertEquals('WeiTest\Db\Member', $this->db->getRecordClass('member'));
        $this->assertInstanceOf('WeiTest\Db\Member', $member);
    }

    public function testCustomRecordClass()
    {
        $this->initFixtures();

        $this->db->setOption('recordClasses', [
            'member' => 'WeiTest\Db\Member',
        ]);

        $member = $this->db->find('member', 1);

        $this->assertEquals('WeiTest\Db\Member', $this->db->getRecordClass('member'));
        $this->assertInstanceOf('WeiTest\Db\Member', $member);
    }

    public function testRecordToArray()
    {
        $this->initFixtures();

        $member = $this->db->find('member', 1)->toArray();

        $this->assertIsArray($member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayHasKey('name', $member);
        $this->assertArrayHasKey('address', $member);

        $member = $this->db->find('member', 1)->toArray(['id', 'group_id']);
        $this->assertIsArray($member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);
        $this->assertArrayNotHasKey('address', $member);

        $member = $this->db->find('member', 1)->toArray(['id', 'group_id', 'notExistField']);
        $this->assertIsArray($member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);
        $this->assertArrayNotHasKey('address', $member);

        $member = $this->db->init('member')->toArray();
        $this->assertIsArray($member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayHasKey('name', $member);
        $this->assertArrayHasKey('address', $member);
        $this->assertNull($member['id']);
        $this->assertNull($member['group_id']);
        $this->assertNull($member['name']);
        $this->assertNull($member['address']);

        $members = $this->db('member')->findAll()->toArray(['id', 'group_id']);
        $this->assertIsArray($members);
        $this->assertArrayHasKey(0, $members);
        $this->assertArrayHasKey('id', $members[0]);
        $this->assertArrayHasKey('group_id', $members[0]);
        $this->assertArrayNotHasKey('name', $members[0]);

        $this->db->setOption('recordClasses', [
            'member' => 'WeiTest\Db\Member',
        ]);
    }

    public function testNewRecordToArrayWithoutReturnFields()
    {
        $this->initFixtures();

        $member = $this->db('member')->findOrInit(['id' => 9999]);

        $this->assertTrue($member->isNew());

        $data = $member->toArray();

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('group_id', $data);
        $this->assertArrayHasKey('name', $data);
    }

    public function testNewRecordToArrayWithReturnFields()
    {
        $this->initFixtures();

        $member = $this->db('member')->findOrInit(['id' => 9999]);

        $this->assertTrue($member->isNew());

        $data = $member->toArray(['group_id', 'name']);

        $this->assertArrayNotHasKey('id', $data);
        $this->assertArrayHasKey('group_id', $data);
        $this->assertArrayHasKey('name', $data);
    }

    public function testToJson()
    {
        $this->initFixtures();
        $member = $this->db->init('member');
        $this->assertIsString($member->toJson());
    }

    public function testDestroyRecord()
    {
        $this->initFixtures();

        $member = $this->db->find('member', 1);

        $result = $member->destroy();

        $this->assertInstanceOf('\Wei\Record', $result);

        $member = $this->db->find('member', 1);

        $this->assertFalse($member);
    }

    public function testDestroyByCondition()
    {
        $this->initFixtures();

        $result = $this->db('member')->destroy(2);

        $this->assertFalse($this->db('member')->find(2));
    }

    public function testGetTable()
    {
        $this->initFixtures();

        $member = $this->db('member')->find('1');

        $this->assertEquals('member', $member->getTable());
    }

    public function testFieldNotFound()
    {
        $this->initFixtures();

        $member = $this->db('member')->find('1');

        $this->setExpectedException(
            '\InvalidArgumentException',
            'Field "notFound" not found in record class "Wei\Record"'
        );

        $member['notFound'];
    }

    public function testCollection()
    {
        $this->initFixtures();

        $members = $this->db->findAll('member');

        $this->assertInstanceOf('\Wei\Record', $members);

        // ToArray
        $memberArray = $members->toArray();
        $this->assertIsArray($memberArray);
        foreach ($memberArray as $member) {
            $this->assertIsArray($member);
        }

        // Filter
        $firstGroupMembers = $members->filter(function ($member) {
            if ('1' == $member['group_id']) {
                return true;
            } else {
                return false;
            }
        });

        $this->assertEquals('1', $firstGroupMembers[0]['group_id']);
        $this->assertInstanceOf('\Wei\Record', $firstGroupMembers);
        $this->assertNotSame($members, $firstGroupMembers);
    }

    public function testFilter()
    {
        $this->initFixtures();

        $this->db->setOption('recordNamespace', 'WeiTest\Db');
        $members = $this->db('member')->findAll();

        $oneMembers = $members->filter(function ($member) {
            return 1 == $member['id'];
        });

        $this->assertEquals(1, $oneMembers->length());
        $this->assertEquals(1, $oneMembers[0]['id']);

        $noMembers = $members->filter(function () {
            return false;
        });

        $this->assertEquals(0, $noMembers->length());
        $this->assertEmpty($noMembers->toArray());
    }

    public function testRecordUnset()
    {
        $this->initFixtures();

        $member = $this->db('member')->find('1');

        $this->assertEquals('twin', $member['name']);
        $this->assertEquals('1', $member['group_id']);

        unset($member['name']);
        $member->remove('group_id');

        $this->assertNull($member['name']);
        $this->assertNull($member['group_id']);
    }

    public function testErrorCodeAndInfo()
    {
        $this->db->errorCode();
        $info = $this->db->errorInfo();

        $this->assertArrayHasKey(0, $info);
        $this->assertArrayHasKey(1, $info);
        $this->assertArrayHasKey(1, $info);
    }

    public function testBeforeAndAfterQuery()
    {
        $this->initFixtures();

        $this->expectOutputRegex('/beforeQueryafterQuery/');

        $this->db->setOption([
            'beforeQuery' => function () {
                echo 'beforeQuery';
            },
            'afterQuery' => function () {
                echo 'afterQuery';
            },
        ]);

        $this->db->find('member', 1);
    }

    public function testBeforeAndAfterQueryForUpdate()
    {
        $this->initFixtures();

        $this->expectOutputString('beforeQueryafterQuery');

        $this->db->setOption([
            'beforeQuery' => function () {
                echo 'beforeQuery';
            },
            'afterQuery' => function () {
                echo 'afterQuery';
            },
        ]);

        $this->db->executeUpdate("UPDATE prefix_member SET name = 'twin2' WHERE id = 1");

        $this->assertEquals("UPDATE prefix_member SET name = 'twin2' WHERE id = 1", $this->db->getLastQuery());
    }

    public function testException()
    {
        $this->setExpectedException('PDOException');

        $this->db->query('SELECT * FROM noThis table');
    }

    public function testExceptionWithParams()
    {
        $this->setExpectedException(
            'PDOException',
            'An exception occurred while executing "SELECT * FROM noThis table WHERE id = ?"'
        );

        $this->db->query('SELECT * FROM noThis table WHERE id = ?', [1]);
    }

    public function testUpdateWithoutParameters()
    {
        $this->initFixtures();

        $result = $this->db->executeUpdate("UPDATE prefix_member SET name = 'twin2' WHERE id = 1");

        $this->assertEquals(1, $result);
    }

    public function testCount()
    {
        $this->initFixtures();

        $count = $this->db('member')->count();

        $this->assertIsInt($count);
        $this->assertEquals(2, $count);

        $count = $this->db('member')->select('id, name')->limit(1)->offset(2)->count();

        $this->assertIsInt($count);
        $this->assertEquals(2, $count);
    }

    public function testCountBySubQuery()
    {
        $this->initFixtures();

        $count = $this->db('member')->countBySubQuery();

        $this->assertIsInt($count);
        $this->assertEquals(2, $count);

        $count = $this->db('member')->select('id, name')->limit(1)->offset(2)->countBySubQuery();

        $this->assertIsInt($count);
        $this->assertEquals(2, $count);
    }

    public function testCountWithCondition()
    {
        $this->initFixtures();

        $count = $this->db('member')->count(1);
        $this->assertIsInt($count);
        $this->assertEquals(1, $count);

        $count = $this->db('member')->count(['id' => 1]);
        $this->assertIsInt($count);
        $this->assertEquals(1, $count);
    }

    public function testParameters()
    {
        $this->initFixtures();

        $db = $this->db;

        $query = $db('member')
            ->where('id = :id AND group_id = :groupId')
            ->setParameters([
                'id' => 1,
                'groupId' => 1,
            ], [
                PDO::PARAM_INT,
                PDO::PARAM_INT,
            ]);
        $member = $query->find();

        $this->assertEquals([
            'id' => 1,
            'groupId' => 1,
        ], $query->getParameters());

        $this->assertEquals(1, $query->getParameter('id'));
        $this->assertNull($query->getParameter('no'));

        $this->assertEquals(1, $member['id']);
        $this->assertEquals(1, $member['group_id']);

        // Set parameter
        $query->setParameter('id', 1, PDO::PARAM_STR);
        $member = $query->find();
        $this->assertEquals(1, $member['id']);

        $query->setParameter('id', 10);
        $member = $query->find();
        $this->assertFalse($member);

        $query = $this
            ->db('member')
            ->andWhere('id = ?', '1', PDO::PARAM_INT);

        $member = $query->find();
        $this->assertEquals('1', $member['id']);
    }

    /**
     * @dataProvider providerForParameterValue
     * @param mixed $value
     */
    public function testParameterValue($value)
    {
        $this->initFixtures();

        $query = $this
            ->db('member')
            ->where('id = ?', $value)
            ->andWhere('id = ?', $value)
            ->andWhere('id = ?', $value)
            ->orWhere('id = ?', $value)
            ->orWhere('id = ?', $value)
            ->groupBy('id')
            ->having('id = ?', $value)
            ->andHaving('id = ?', $value)
            ->andHaving('id = ?', $value)
            ->orHaving('id = ?', $value)
            ->orHaving('id = ?', $value);

        // No error raise
        $array = $query->fetchAll();
        $this->assertIsArray($array);
    }

    public function providerForParameterValue()
    {
        return [
            ['0'],
            [0],
            [null],
            [true],
            [[null]],
        ];
    }

    public function testGetAndResetAllSqlParts()
    {
        $query = $this->db('member')->offset(1)->limit(1);

        $this->assertEquals(1, $query->getSqlPart('offset'));
        $this->assertEquals(1, $query->getSqlPart('limit'));

        $queryParts = $query->getSqlParts();
        $this->assertArrayHasKey('offset', $queryParts);
        $this->assertArrayHasKey('limit', $queryParts);

        $query->resetSqlParts();

        $this->assertFalse($query->getSqlPart('offset'));
        $this->assertFalse($query->getSqlPart('limit'));
    }

    public function testGetTableFromQueryBuilder()
    {
        $qb = $this->db('member');
        $this->assertEquals('member', $qb->getTable());

        $qb->from('member m');
        $this->assertEquals('member', $qb->getTable());

        $qb->from('member m');
        $this->assertEquals('member', $qb->getTable());

        $qb->from('member AS m');
        $this->assertEquals('member', $qb->getTable());
    }

    public function testDbCount()
    {
        $this->initFixtures();

        $db = $this->db;

        $count = $db->count('member');
        $this->assertIsInt($count);
        $this->assertEquals(2, $count);

        $count = $db->count('member', ['id' => '1']);
        $this->assertIsInt($count);
        $this->assertEquals(1, $count);

        $count = $db->count('member', ['id' => '1']);
        $this->assertIsInt($count);
        $this->assertEquals(1, $count);

        $count = $db->count('member', ['id' => '123']);
        $this->assertIsInt($count);
        $this->assertEquals(0, $count);
    }

    public function testTablePrefix()
    {
        $this->initFixtures();

        $db = $this->db;

        $db->setTablePrefix('tbl_');
        $this->assertEquals('tbl_member', $db->getTable('member'));

        $db->setTablePrefix('prefix_post_');
        $this->assertEquals(3, $db->count('tag'));
    }

    public function testTablePrefixWithDbName()
    {
        $db = $this->db;

        $db->setTablePrefix('tbl_');
        $this->assertSame('db2.tbl_member', $db->getTable('db2.member'));
    }

    public function testConnectFails()
    {
        $this->setExpectedException('\PDOException');
        $db = new \Wei\Db([
            'wei' => $this->wei,
            'driver' => 'mysql',
            'host' => '255.255.255.255',
            'dbname' => 'test',
            'connectFails' => function ($db, $exception) {
                $this->assertTrue(true);
                $this->assertInstanceOf('PDOException', $exception);
            },
        ]);
        $db->connect();
    }

    public function testGlobalOption()
    {
        $fn = function () {
        };
        $this->wei->setConfig([
            // mysql
            'db' => [
                'beforeConnect' => $fn,
            ],
            'sqlite:db' => [
                'beforeConnect' => $fn,
            ],
            'pgsql:db' => [
                'beforeConnect' => $fn,
            ],
            'cb:db' => [
                'db' => $this->db,
                'global' => true,
            ],
        ]);

        $this->assertSame($fn, $this->db->getOption('beforeConnect'));
        $this->assertSame($fn, $this->cbDb->getOption('beforeConnect'));

        // Remove all relation configuration
        $this->cbDb = null;
        $this->wei->remove('cbDb');
        $this->wei->setConfig('cb:db', [
            'db' => null,
        ]);
    }

    public function testUnsupportedDriver()
    {
        $this->setExpectedException('\RuntimeException', 'Unsupported database driver: abc');

        $db = new \Wei\Db([
            'wei' => $this->wei,
            'driver' => 'abc',
        ]);

        $db->query('SELECT MAX(1, 2)');
    }

    public function testCustomDsn()
    {
        $db = new \Wei\Db([
            'wei' => $this->wei,
            'dsn' => 'sqlite::memory:',
        ]);

        $this->assertEquals('sqlite::memory:', $db->getDsn());
    }

    public function testInsertBatch()
    {
        $this->initFixtures();

        $result = $this->db->batchInsert('member', [
            [
                'group_id' => '1',
                'name' => 'twin',
                'address' => 'test',
            ],
            [
                'group_id' => '1',
                'name' => 'test',
                'address' => 'test',
            ],
        ]);

        $this->assertEquals(2, $result);
    }

    public function testInsertBatchFalseWillConvertTo0()
    {
        $this->initFixtures();

        $result = $this->db->batchInsert('member', [
            [
                'group_id' => false,
                'name' => 'twin',
                'address' => 'test',
            ],
            [
                'group_id' => false,
                'name' => 'test',
                'address' => 'test',
            ],
        ]);
        $this->assertEquals(2, $result);

        $lastId = $this->db->lastInsertId();
        $member = $this->db->select('member', $lastId);
        $this->assertSame('0', $member['group_id']);
    }

    public function testSlaveDb()
    {
        // Generate slave db configuration name
        $driver = $this->db->getDriver();
        $configName = $driver . 'Slave:db';

        // Set configuration for slave db
        $options = $this->wei->getConfig('db');
        $this->wei->setConfig($configName, $options);

        $this->db->setOption('slaveDb', $configName);

        $query = 'SELECT 1 + 2';
        $this->db->query($query);

        // Receives the slave db wei
        /** @var $slaveDb \Wei\Db */
        $slaveDb = $this->wei->get($configName);

        // Test that the query is execute by slave db, not the master db
        $this->assertNotContains($query, $this->db->getQueries());
        $this->assertContains($query, $slaveDb->getQueries());
    }

    public function testReload()
    {
        $this->db->setOption('recordNamespace', 'WeiTest\Db');
        $this->initFixtures();

        /** @var $member2 \WeiTest\Db\Member */
        $member = $this->db->find('member', 1);
        /** @var $member2 \WeiTest\Db\Member */
        $member2 = $this->db->find('member', 1);

        $member['group_id'] = 2;
        $member->save();

        $this->assertNotEquals($member['group_id'], $member2['group_id']);
        $this->assertEquals(1, $member->getLoadTimes());

        $member2->reload();
        $this->assertEquals($member['group_id'], $member2['group_id']);
        $this->assertEquals(2, $member2->getLoadTimes());
    }

    public function testFindOne()
    {
        $this->initFixtures();

        $record = $this->db->findOne('member', 1);
        $this->assertInstanceOf('\Wei\Record', $record);
    }

    public function testFindOneWithException()
    {
        $this->initFixtures();

        $this->setExpectedException('Exception', 'Record not found', 404);

        $this->db->findOne('member', 999);
    }

    public function testisChanged()
    {
        $this->initFixtures();

        $member = $this->db->init('member');
        $this->assertFalse($member->isChanged());

        $member['name'] = 'tt';
        $member['group_id'] = '1';
        $member['address'] = 'address';
        $this->assertFalse($member->isChanged('id'));
        $this->assertTrue($member->isChanged('name'));
        $this->assertTrue($member->isChanged());

        $this->assertNull($member->getChangedData('name'));

        $member['name'] = 'aa';
        $this->assertTrue($member->isChanged());
        $this->assertEquals('tt', $member->getChangedData('name'));

        $member->save();
        $this->assertFalse($member->isChanged());
        $this->assertEmpty($member->getChangedData());
    }

    public function testReconnect()
    {
        $this->db->connect();
        $pdo = $this->db->getOption('pdo');

        $this->db->reconnect();
        $newPdo = $this->db->getOption('pdo');

        $this->assertEquals($pdo, $newPdo);
        $this->assertNotSame($pdo, $newPdo);
    }

    public function testGetter()
    {
        wei([
            'test:db' => [
                'user' => 'user',
                'password' => 'password',
                'host' => 'host',
                'port' => 'port',
                'dbname' => 'dbname',
            ],
        ]);

        /** @var $testDb \Wei\Db */
        $testDb = $this->testDb;

        $this->assertEquals('user', $testDb->getUser());
        $this->assertEquals('password', $testDb->getPassword());
        $this->assertEquals('host', $testDb->getHost());
        $this->assertEquals('port', $testDb->getPort());
        $this->assertEquals('dbname', $testDb->getDbname());
    }

    public function testQueryBuilderForEach()
    {
        $this->initFixtures();

        $members = $this->db('member')->where('group_id = 1');
        foreach ($members as $member) {
            $this->assertEquals(1, $member['group_id']);
        }
    }

    public function testInsertWithRawValue()
    {
        $this->initFixtures();

        $this->db->insert('member', [
            'group_id' => '1',
            'name' => $this->db->raw('1 + 1'),
            'address' => 'test',
        ]);

        $id = $this->db->lastInsertId('prefix_member_id_seq');
        $member = $this->db->select('member', $id);

        $this->assertNotEquals('1 + 1', $member['name']);
        $this->assertEquals('2', $member['name']);
    }

    public function testInsertFalseWillConvertTo0()
    {
        $this->initFixtures();

        $this->db->insert('member', [
            'group_id' => false,
            'name' => false,
            'address' => '',
        ]);

        $id = $this->db->lastInsertId('prefix_member_id_seq');
        $member = $this->db->select('member', $id);

        $this->assertSame('0', $member['group_id']);
        $this->assertSame('0', $member['name']);
    }

    public function testUpdateFalseWillConvertTo0()
    {
        $this->initFixtures();

        $this->db->update('member', ['group_id' => false, 'name' => false], ['id' => 1]);

        $member = $this->db->select('member', 1);

        $this->assertSame('0', $member['group_id']);
        $this->assertSame('0', $member['name']);
    }

    public function testUpdateWithRawValue()
    {
        $this->initFixtures();

        $this->db->update(
            'member',
            ['group_id' => $this->db->raw('group_id + 1')],
            ['id' => $this->db->raw('0.5 + 0.5')]
        );

        $member = $this->db->select('member', 1);

        $this->assertEquals('2', $member['group_id']);
    }

    public function testDeleteWithSqlObject()
    {
        $this->initFixtures();

        $result = $this->db->delete('member', ['id' => $this->db->raw('0.5 + 0.5')]);

        $this->assertEquals(1, $result);
        $this->assertFalse($this->db->select('member', 1));
    }

    public function testRecordWithSqlObject()
    {
        $this->initFixtures();

        $member = $this->db->find('member', 1);
        $groupId = $member['group_id'];

        $member['group_id'] = $this->db->raw('group_id + 1');
        $member->save();
        $member->reload();

        $this->assertEquals($groupId + 1, $member['group_id']);
    }

    public function testGetTableFieldsButTableNotExists()
    {
        $this->setExpectedException('PDOException');
        $this->db->getTableFields('notExists');
    }

    public function testNewRecord()
    {
        $this->initFixtures();

        // Use record as array
        $member = $this->db('member')->where('id = 1');
        $this->assertEquals('1', $member['id']);

        // Use record as 2d array
        $members = $this->db('member')->where('group_id = 1');
        foreach ($members as $member) {
            $this->assertEquals(1, $member['group_id']);
        }

        $member1 = $this->db('member');
        $member2 = $this->db('member');
        $this->assertEquals($member1, $member2);
        $this->assertNotSame($member1, $member2);
    }

    public function testCreateRecord()
    {
        $this->initFixtures();

        $member = $this->db('member');

        $data = $member->toArray();
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('group_id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('address', $data);

        $member->fromArray([
            'group_id' => 1,
            'name' => 'John',
            'address' => 'xx street',
        ]);
        $result = $member->save();

        $this->assertSame($result, $member);
    }

    public function testBeforeAndAfterCreateCallbacks()
    {
        $this->initFixtures();

        $this->db->setOption('recordNamespace', 'WeiTest\Db');

        $member = $this->db('member')->fromArray([
            'group_id' => 1,
            'name' => 'twin',
            'address' => 'xx street',
        ]);

        $member->save();

        $this->assertEquals('beforeSave->beforeCreate->afterCreate->afterSave', $member->getEventResult());
    }

    public function testBeforeAndAfterDestroyCallbacks()
    {
        $this->initFixtures();

        $this->db->setOption('recordNamespace', 'WeiTest\Db');

        $member = $this->db->find('member', 1);

        $member->destroy();

        $this->assertEquals('beforeDestroy->afterDestroy', $member->getEventResult());
    }

    public function testCreateCollection()
    {
        $this->initFixtures();

        $members = $this->db('member');

        $members->fromArray([
            [
                'group_id' => 1,
                'name' => 'John',
                'address' => 'xx street',
            ],
            [
                'group_id' => 2,
                'name' => 'Tome',
                'address' => 'xx street',
            ],
        ]);

        $this->assertSame(1, $members[0]['group_id']);
    }

    public function testFindRecordAndDestroy()
    {
        $this->initFixtures();

        $member = $this->db('member')->find(['id' => 1]);
        $result = $member->destroy();

        $this->assertInstanceOf('\Wei\Record', $result);

        $member = $this->db('member')->find(['id' => 1]);
        $this->assertFalse($member);
    }

    public function testDeleteRecordByQueryBuilder()
    {
        $this->initFixtures();

        $result = $this->db('member')->where('group_id = ?', 1)->delete();
        $this->assertEquals(2, $result);

        $result = $this->db('member')->delete(['group_id' => 1]);
        $this->assertEquals(0, $result);
    }

    public function testFindCollectionAndDestroy()
    {
        $this->initFixtures();

        $members = $this->db('member')->where('group_id = 1');
        $members->destroy();

        $members = $this->db('member')->where('group_id = 1');
        $this->assertEquals(0, count($members));
    }

    public function testFindRecordAndUpdate()
    {
        $this->initFixtures();

        $member = $this->db('member')->find(['id' => 1]);
        $member['name'] = 'William';
        $result = $member->save();
        $this->assertSame($result, $member);

        $member = $this->db('member')->find(['id' => 1]);
        $this->assertEquals('William', $member['name']);
    }

    public function testFindCollectionAndUpdate()
    {
        $this->initFixtures();

        $members = $this->db('member')->where('group_id = 1');
        $number = $members->length();
        $this->assertEquals(2, $number);

        foreach ($members as $member) {
            $member['group_id'] = 2;
        }
        $members->save();

        $members = $this->db('member')->where('group_id = 2');
        $this->assertEquals(2, $members->length());
    }

    public function testCreateCollectionAndSave()
    {
        $this->initFixtures();

        // Creates a member collection
        $members = $this->db('member');

        $john = $this->db('member')->fromArray([
            'group_id' => 2,
            'name' => 'John',
            'address' => 'xx street',
        ]);

        $larry = $this->db('member')->fromArray([
            'group_id' => 3,
            'name' => 'Larry',
            'address' => 'xx street',
        ]);

        // Adds record to collection
        $members->fromArray([
            $john,
        ]);

        // Or adds by [] operator
        $members[] = $larry;

        /** @var $members \Wei\Record */
        $result = $members->save();

        $this->assertSame($result, $members);

        // Find out member by id
        $members = $this->db('member')->indexBy('id')->where(['id' => [$john['id'], $larry['id']]]);

        $this->assertEquals('John', $members[$john['id']]['name']);
        $this->assertEquals('Larry', $members[$larry['id']]['name']);
    }

    public function testDestroyRecordAndFindAgainReturnFalse()
    {
        $this->initFixtures();

        $member = $this->db('member');
        $result = $member->find(['id' => 1])->destroy();

        $this->assertInstanceOf('\Wei\Record', $result);

        $member = $this->db('member')->find(['id' => 1]);
        $this->assertFalse($member);
    }

    public function testSaveOnNoFiledChanged()
    {
        $this->initFixtures();
        $record = $this->db->init('member', ['id' => 1], false);
        $record = $record->save();

        $this->assertInstanceOf('\Wei\Record', $record);
    }

    public function testPrimaryKey()
    {
        $this->initFixtures();

        $record = $this->db->init('member');
        $this->assertEquals('id', $record->getPrimaryKey());

        $record->setPrimaryKey('testId');
        $this->assertEquals('testId', $record->getPrimaryKey());
    }

    public function testIsNew()
    {
        $this->initFixtures();

        $record = $this->db->init('member', ['id' => 1], true);
        $this->assertTrue($record->isNew());

        $record = $this->db->init('member', ['id' => 1], false);
        $this->assertFalse($record->isNew());
    }

    public function testFindByPrimaryKey()
    {
        $this->initFixtures();

        $record = $this->db('member')->find(1);
        $this->assertEquals(1, $record['id']);

        $record = $this->db('member')->find('1');
        $this->assertEquals(1, $record['id']);
    }

    public function testInvalidLimit()
    {
        $this->initFixtures();
        $member = $this->db('member');

        $member->limit(-1);
        $this->assertEquals(1, $member->getSqlPart('limit'));

        $member->limit(0);
        $this->assertEquals(1, $member->getSqlPart('limit'));

        $member->limit('string');
        $this->assertEquals(1, $member->getSqlPart('limit'));
    }

    public function testInvalidOffset()
    {
        $this->initFixtures();
        $member = $this->db('member');

        $member->offset(-1);
        $this->assertEquals(0, $member->getSqlPart('offset'));

        $member->offset(-1.1);
        $this->assertEquals(0, $member->getSqlPart('offset'));

        $member->offset('string');
        $this->assertEquals(0, $member->getSqlPart('offset'));

        $member->offset(9848519079999155811);
        $this->assertEquals(0, $member->getSqlPart('offset'));
    }

    public function testInvalidPage()
    {
        $this->initFixtures();
        $member = $this->db('member');

        // @link http://php.net/manual/en/language.types.integer.php#language.types.integer.casting.from-float
        // (984851907999915581 - 1) * 10
        // => 9.8485190799992E+18
        // => (int)9.8485190799992E+18
        // => -8598224993710352384
        // => 0
        $member->page(984851907999915581);
        $this->assertEquals(0, $member->getSqlPart('offset'));
    }

    public function testMax()
    {
        $this->initFixtures();

        $result = $this->db->max('member', 'id');
        $this->assertIsFloat($result);
        $this->assertEquals(2, $result);
    }

    public function testMin()
    {
        $this->initFixtures();

        $result = $this->db->min('member', 'id');
        $this->assertIsFloat($result);
        $this->assertEquals(1, $result);
    }

    public function testAvg()
    {
        $this->initFixtures();

        $result = $this->db->avg('member', 'id');
        $this->assertIsFloat($result);
        $this->assertEquals(1.5, $result);
    }

    public function testSaveDestroyRecord()
    {
        $this->initFixtures();

        $member = $this->db->find('member', 1);
        $member->destroy();

        $member->save();

        $member = $this->db->find('member', 1);
        $this->assertFalse($member);
    }

    public function testSaveWithNullPrimaryKey()
    {
        $this->initFixtures();

        $member = $this->db('member');
        $member->save([
            'id' => null,
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test',
        ]);

        $this->assertNotNull($member['id']);

        $member = $this->db('member');
        $member->save([
            'id' => '',
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test',
        ]);

        $this->assertNotNull($member['id']);
    }

    public function testNullAsCollectionKey()
    {
        $this->initFixtures();

        $members = $this->db('member');

        $members[] = $this->db('member');
        $members[] = $this->db('member');
        $members[] = $this->db('member');
        $members[] = $this->db('member');

        $this->assertEquals(4, $members->length());
    }

    public function testSetDataWithProperty()
    {
        $this->initFixtures();

        $member = $this->db('member');

        $member['table'] = 234;

        $this->assertNotEquals(234, $member->getTable());
        $this->assertEquals('member', $member->getTable());
    }

    public function testAddNotRecordToCollection()
    {
        $this->initFixtures();

        $members = $this->db('member');
        $member = $this->db('member');

        // Make sure $members is a collection
        $members[] = $member;

        $this->setExpectedException(
            'InvalidArgumentException',
            'Value for collection must be an instance of Wei\Record'
        );

        // Assign non record value to raise an exception
        $members[] = 234;
    }

    public function testGetPdo()
    {
        $this->assertInstanceOf('PDO', $this->db->getPdo());
    }

    public function testIncrAndDecr()
    {
        $this->initFixtures();

        $member = $this->db('member')->find(1);

        $groupId = $member['group_id'];

        $member->incr('group_id', 2);
        $member->save();
        $member->reload();

        $this->assertEquals($groupId + 2, $member['group_id']);

        $member->decr('group_id');
        $member->save();
        $member->reload();

        $this->assertEquals($groupId + 2 - 1, $member['group_id']);
    }

    public function testCreateOrUpdate()
    {
        $this->initFixtures();

        $id = null;
        $member = $this->db('member')->findOrInit($id, [
            'group_id' => 2,
            'name' => 'twin',
            'address' => 'xx street',
        ]);

        $this->assertTrue($member->isNew());
        $this->assertEquals(2, $member['group_id']);

        $member = $this->db('member')->findOrInit(1, [
            'group_id' => 2,
            'name' => 'twin',
            'address' => 'xx street',
        ]);

        $this->assertFalse($member->isNew());
    }

    public function testDetach()
    {
        $this->initFixtures();

        /** @var $member \Wei\Record */
        $member = $this->db('member')->findById(1);

        $this->assertFalse($member->isDetached());

        $member->detach();

        $this->assertTrue($member->isDetached());

        $member->save();

        $this->assertTrue($member->isDestroyed());

        $newMember = $this->db('member')->findById(1);

        $this->assertFalse($newMember);
    }

    public function testRecordFetchColumn()
    {
        $this->initFixtures();

        $count = $this->db('member')->select('COUNT(id)')->fetchColumn();
        $this->assertEquals(2, $count);

        $count = $this->db('member')->select('COUNT(id)')->fetchColumn(['id' => 1]);
        $this->assertEquals(1, $count);
    }

    public function testFillable()
    {
        $this->initFixtures();

        /** @var $member \Wei\Record */
        $member = $this->db('member');

        $member->setOption('fillable', ['name']);
        $this->assertTrue($member->isFillable('name'));

        $member->fromArray([
            'id' => '1',
            'name' => 'name',
        ]);

        $this->assertNull($member['id']);
        $this->assertEquals('name', $member['name']);
    }

    public function testGuarded()
    {
        $this->initFixtures();

        /** @var $member \Wei\Record */
        $member = $this->db('member');

        $member->setOption('guarded', ['id', 'name']);

        $this->assertFalse($member->isFillable('id'));
        $this->assertFalse($member->isFillable('name'));

        $member->fromArray([
            'id' => '1',
            'group_id' => '2',
            'name' => 'name',
        ]);

        $this->assertNull($member['id']);
        $this->assertEquals('2', $member['group_id']);
        $this->assertNull($member['name']);
    }

    public function testCache()
    {
        $this->initFixtures();

        $member = $this->getMemberFromCache(1);
        $this->assertEquals('twin', $member['name']);

        $member->save([
            'name' => 'twin2',
        ]);

        $member = $this->getMemberFromCache(1);
        $this->assertEquals('twin', $member['name']);

        $member->clearTagCache();

        $member = $this->getMemberFromCache(1);
        $this->assertEquals('twin2', $member['name']);

        wei()->cache->clear();
    }

    public function testCacheWithJoin()
    {
        $this->initFixtures();

        $member = $this->db('member')
            ->select('prefix_member.*')
            ->leftJoin('prefix_member_group', 'prefix_member.group_id = prefix_member_group.id')
            ->where('prefix_member.id = 1')
            ->tags()
            ->cache();

        // Fetch from db
        $data = $member->fetch();
        $this->assertEquals('twin', $data['name']);

        $this->db('member')->where('id = 1')->update("name = 'twin2'");

        // Fetch from cache
        $data = $member->fetch();
        $this->assertEquals('twin', $data['name']);

        // Clear cache
        wei()->tagCache('prefix_member')->clear();
        wei()->tagCache('prefix_member', 'prefix_member_group')->reload();

        // Fetch from db
        $data = $member->fetch();
        $this->assertEquals('twin2', $data['name']);
    }

    public function testCustomCacheTags()
    {
        $this->initFixtures();

        $member = $this->db('member')
            ->select('prefix_member.*')
            ->leftJoin('prefix_member_group', 'prefix_member.group_id = prefix_member_group.id')
            ->where('prefix_member.id = 1')
            ->tags(['member', 'member_group'])
            ->cache();

        // Fetch from db
        $data = $member->fetch();
        $this->assertEquals('twin', $data['name']);

        $this->db('member')->where('id = 1')->update("name = 'twin2'");

        // Fetch from cache
        $data = $member->fetch();
        $this->assertEquals('twin', $data['name']);

        // Clear cache
        wei()->tagCache('member')->clear();
        wei()->tagCache('member', 'member_group')->reload();

        // Fetch from db
        $data = $member->fetch();
        $this->assertEquals('twin2', $data['name']);

        wei()->cache->clear();
    }

    public function testCustomCacheKey()
    {
        $this->initFixtures();

        $member = $this->db('member')->cache()->setCacheKey('member-1')->tags(false)->find(['id' => 1]);

        $this->assertEquals(1, $member['id']);

        $cacheData = wei()->cache->get('member-1');
        $this->assertEquals('1', $cacheData[0]['id']);

        wei()->cache->clear();
    }

    public function testUpdateWithParam()
    {
        $this->initFixtures();

        $row = $this->db('member')->update(['address' => 'test address']);
        $this->assertEquals(2, $row);

        $member = $this->db('member')->find();
        $this->assertEquals('test address', $member['address']);

        // Update with where clause
        $row = $this->db('member')->where(['name' => 'twin'])->update(['address' => 'test address 2']);
        $this->assertEquals(1, $row);

        $member = $this->db('member')->findOne(['name' => 'twin']);
        $this->assertEquals('test address 2', $member['address']);

        // Update with two where clauses
        $row = $this->db('member')
            ->where(['name' => 'twin'])
            ->andWhere(['group_id' => 1])
            ->update(['address' => 'test address 3']);
        $this->assertEquals(1, $row);

        $member = $this->db('member')->findOne(['name' => 'twin']);
        $this->assertEquals('test address 3', $member['address']);
    }

    public function testEmptyFrom()
    {
        $sql = $this->db('member')->resetSqlPart('from')->getSql();
        $this->assertEquals('SELECT * FROM p_member', $sql);

        $sql = $this->db('member')->from('member m')->getSql();
        $this->assertEquals('SELECT * FROM p_member m', $sql);
    }

    public function testUseDb()
    {
        if ('mysql' === $this->db->getDriver()) {
            $this->db->useDb('information_schema');
            $this->assertEquals('information_schema', $this->db->getDbname());
        } else {
            $this->expectExceptionObject(
                new RuntimeException('Unsupported switching database for current driver: ' . $this->db->getDriver())
            );
            $this->db->useDb('information_schema');
        }
    }

    public function testSetTablePrefix()
    {
        $prefix = $this->db->getTablePrefix();
        $this->db->setTablePrefix('custom_prefix');
        $this->assertSame('custom_prefix', $this->db->getTablePrefix());
        $this->db->setTablePrefix($prefix);
    }

    protected function createTable()
    {
        $db = $this->db;

        $db->query('CREATE TABLE prefix_member_group (
        id INTEGER NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_member (
        id INTEGER NOT NULL AUTO_INCREMENT,
        group_id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        address VARCHAR(256) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_post (
        id INTEGER NOT NULL AUTO_INCREMENT,
        member_id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_tag (
        id INTEGER NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_post_tag (
        post_id INTEGER NOT NULL,
        tag_id INTEGER NOT NULL)');
    }

    protected function dropTable()
    {
        $db = $this->db;
        $db->query('DROP TABLE IF EXISTS prefix_member_group');
        $db->query('DROP TABLE IF EXISTS prefix_member');
        $db->query('DROP TABLE IF EXISTS prefix_post');
        $db->query('DROP TABLE IF EXISTS prefix_tag');
        $db->query('DROP TABLE IF EXISTS prefix_post_tag');
    }

    protected function getMemberFromCache($id)
    {
        return $this->db('member')->cache(600)->findById($id);
    }
}
