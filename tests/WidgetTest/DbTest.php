<?php

namespace WidgetTest;

use Widget\DB\QueryBuilder;
use PDO;

/**
 * @property \Widget\Db db
 * @method \Widget\Db\QueryBuilder db($table)
 */
class DbTest extends TestCase
{
    protected function createTable()
    {
        $db = $this->db;
        $db->query("CREATE TABLE member_group (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE member (id INTEGER NOT NULL, group_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE post (id INTEGER NOT NULL, member_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE tag (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE post_tag (post_id INTEGER NOT NULL, tag_id INTEGER NOT NULL)");
    }

    protected function dropTable()
    {
        $db = $this->db;
        $db->query('DROP TABLE IF EXISTS member_group');
        $db->query('DROP TABLE IF EXISTS member');
        $db->query('DROP TABLE IF EXISTS post');
        $db->query('DROP TABLE IF EXISTS tag');
        $db->query('DROP TABLE IF EXISTS post_tag');
    }

    public function initFixtures()
    {
        $db = $this->db;
        $this->dropTable();
        $this->createTable();

        $db->insert('member_group', array(
            'id' => '1',
            'name' => 'vip'
        ));

        $db->insert('member', array(
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test'
        ));

        $db->insert('member', array(
            'group_id' => '1',
            'name' => 'test',
            'address' => 'test'
        ));

        $db->insert('post', array(
            'member_id' => '1',
            'name' => 'my first post',
        ));

        $db->insert('post', array(
            'member_id' => '1',
            'name' => 'my second post',
        ));

        $db->insert('tag', array(
            'id' => '1',
            'name' => 'database'
        ));

        $db->insert('tag', array(
            'id' => '2',
            'name' => 'PHP'
        ));

        $db->insert('post_tag', array(
            'post_id' => '1',
            'tag_id' => '1',
        ));

        $db->insert('post_tag', array(
            'post_id' => '1',
            'tag_id' => '2',
        ));

        $db->insert('post_tag', array(
            'post_id' => '2',
            'tag_id' => '1',
        ));
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
        $this->assertInstanceOf('\Widget\Db\Record', $this->db->create('member'));
    }

    public function testRelation()
    {
        $this->initFixtures();

        $db = $this->db;

        $db->setOption('recordNamespace', 'WidgetTest\Db');

        /** @var $member \WidgetTest\Db\Member */
        $member = $db->member('1');

        $this->assertInstanceOf('\Widget\Db\Record', $member);

        $this->assertEquals('1', $member->id);
        $this->assertEquals('twin', $member->name);
        $this->assertEquals('test', $member->address);
        $this->assertEquals('1', $member->group_id);

        // Relation one-to-one
        $post = $member->getPost();

        $this->assertInstanceOf('\Widget\Db\Record', $post);

        $this->assertEquals('1', $post->id);
        $this->assertEquals('my first post', $post->name);
        $this->assertEquals('1', $post->member_id);

        // Relation belong-to
        $group = $member->getGroup();

        $this->assertInstanceOf('\Widget\Db\Record', $group);

        $this->assertEquals('1', $group->id);
        $this->assertEquals('vip', $group->name);

        // Relation one-to-many
        $posts = $member->getPosts();

        $this->assertInstanceOf('\Widget\Db\Collection', $posts);

        $firstPost = $posts[0];
        $this->assertInstanceOf('\Widget\Db\Record', $firstPost);

        $this->assertEquals('1', $firstPost->id);
        $this->assertEquals('my first post', $firstPost->name);
        $this->assertEquals('1', $firstPost->member_id);
    }

    public function testSet()
    {
        $this->initFixtures();

        $member = $this->db->member('1');

        $this->assertEquals('1', $member->id);

        $member->id = 2;

        $this->assertEquals('2', $member->id);
    }

    public function testRecordArrayAccess()
    {
        $this->initFixtures();

        /** @var $member \WidgetTest\Db\Member */
        $member = $this->db->member('1');

        $this->assertEquals(1, $member['id']);

        $member['name'] = 'test';
        $this->assertEquals('test', $member['name']);
        $this->assertEquals($member->name, $member['name']);

        $this->assertTrue(isset($member['name']));

        unset($member['name']);
        $this->assertNull($member['name']);

        $this->assertFalse(isset($member['name']));
    }

    public function testGetRelation()
    {
        $this->initFixtures();

        $db = $this->db;

        $member = $db->member('1');

        $post = $member->post = $db->find('post', array('member_id' => $member->id));

        $this->assertInstanceOf('\Widget\Db\Record', $post);

        $this->assertEquals('1', $post->id);
        $this->assertEquals('my first post', $post->name);
        $this->assertEquals('1', $post->member_id);
    }

    public function testUpdate()
    {
        $this->initFixtures();

        $this->db->update('member', array('name' => 'hello'), array('id' => '1'));

        $member = $this->db->find('member', '1');

        $this->assertEquals('hello', $member->name);
    }

    public function testDelete()
    {
        $this->initFixtures();

        $this->db->delete('member', array('id' => '1'));

        $member = $this->db->find('member', 1);

        $this->assertFalse($member);
    }

    public function testFind()
    {
        $this->initFixtures();

        $member = $this->db->find('member', '1');

        $this->assertEquals('1', $member->id);
    }

    public function testFindOrCreate()
    {
        $this->initFixtures();

        $member = $this->db->find('member', '3');
        $this->assertFalse($member);

        // Not found and create new object
        $member = $this->db->findOrCreate('member', '3', array(
            'name' => 'name'
        ));
        $this->assertEquals('name', $member->name);
        $this->assertEquals('3', $member->id);

        // Found
        $member = $this->db->findOrCreate('member', '2');

        $this->assertEquals('2', $member->id);

        $member = $this->db->findOrCreate('member', '3', array(
            'id' => '1', // Would be overwrite
            'name' => 'twin'
        ));

        $this->assertNotEquals('1', $member->id);
        $this->assertEquals('3', $member->id);

        $member = $this->db->findOrCreate('member', array(
            'group_id' => '1',
            'name' => 'twin2',
        ));

        $this->assertEquals('1', $member->group_id);
        $this->assertEquals('twin2', $member->name);
    }

    public function testRecordSave()
    {
        $this->initFixtures();

        $db = $this->db;

        // Existing member
        $member = $db->member('1');
        $member->address = 'address';
        $result = $member->save();

        $this->assertTrue($result);
        $this->assertEquals('1', $member->id);

        // New member save with data
        $member = $db->create('member');
        $this->assertTrue($member->isNew());
        $member->fromArray(array(
            'group_id' => '1',
            'name' => 'save',
            'address' => 'save'
        ));
        $result = $member->save();
        $this->assertFalse($member->isNew());

        $this->assertTrue($result);
        $this->assertEquals('3', $member->id);
        $this->assertEquals('save', $member->name);

        // Save again
        $member->address = 'address3';
        $result = $member->save();
        $this->assertTrue($result);
        $this->assertEquals('3', $member->id);
    }

    public function testSelect()
    {
        $this->initFixtures();

        $data = $this->db->select('member', 1);
        $this->assertEquals('twin', $data['name']);

        // Empty array as conditions
        $data = $this->db->select('member', array());
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

        $data = $this->db->selectAll('member', array('name' => 'twin'));

        $this->assertCount(1, $data);

        $data = $this->db->selectAll('member');

        $this->assertCount(2, $data);
    }

    public function testFetch()
    {
        $this->initFixtures();

        $data = $this->db->fetch("SELECT * FROM member WHERE name = ?", 'twin');
        $this->assertInternalType('array', $data);
        $this->assertEquals('twin', $data['name']);
        $this->assertEquals("SELECT * FROM member WHERE name = ?", $this->db->getLastSql());

        $data = $this->db->fetch("SELECT * FROM member WHERE name = ?", 'notFound');
        $this->assertFalse($data);
        $this->assertEquals("SELECT * FROM member WHERE name = ?", $this->db->getLastSql());

        $data = $this->db->fetch("SELECT * FROM member WHERE name = :name", array('name' => 'twin'));
        $this->assertInternalType('array', $data);
        $this->assertEquals('twin', $data['name']);

        $data = $this->db->fetch("SELECT * FROM member WHERE name = :name", array(':name' => 'twin'));
        $this->assertInternalType('array', $data);
        $this->assertEquals('twin', $data['name']);
    }

    public function testFetchAll()
    {
        $this->initFixtures();

        $data = $this->db->fetchAll("SELECT * FROM member WHERE group_id = ?", '1');

        $this->assertInternalType('array', $data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testQueryFetch()
    {
        $this->initFixtures();

        $data = $this->db('member')->where('id = 1')->fetch();
        $this->assertInternalType('array', $data);
        $this->assertEquals('1', $data['id']);
    }

    public function testQueryFetchAll()
    {
        $this->initFixtures();

        $data = $this->db('member')->fetchAll();

        $this->assertInternalType('array', $data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testGetRecordClass()
    {
        $db = $this->db;

        $db->setOption('recordNamespace', 'WidgetTest\Db');

        $this->assertEquals('WidgetTest\Db\Member', $db->getRecordClass('member'));
        $this->assertEquals('WidgetTest\Db\Member', $db->getRecordClass('member'));
        $this->assertEquals('WidgetTest\Db\MemberGroup', $db->getRecordClass('member_group'));
        $this->assertEquals('WidgetTest\Db\MemberGroup', $db->getRecordClass('memberGroup'));
        $this->assertEquals('WidgetTest\Db\MemberGroup', $db->getRecordClass('member_Group'));
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

        $this->assertEquals("SELECT * FROM member WHERE name = 'twin' LIMIT 1", $query->getSql());
        $this->assertEquals('twin', $member->name);

        // ? conditions
        $query = $this->db('member')->where('name = ?', 'twin');
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE name = ? LIMIT 1", $query->getSql());
        $this->assertEquals('twin', $member->name);

        $query = $this->db('member')->where('group_id = ? AND name = ?', array('1', 'twin'));
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE group_id = ? AND name = ? LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);
        $this->assertEquals('twin', $member->name);

        // : conditions
        $query = $this->db('member')->where('group_id = :groupId AND name = :name', array(
            'groupId' => '1',
            'name' => 'twin'
        ));
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE group_id = :groupId AND name = :name LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);
        $this->assertEquals('twin', $member->name);

        $query = $this->db('member')->where('group_id = :groupId AND name = :name', array(
            ':groupId' => '1',
            ':name' => 'twin'
        ));
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE group_id = :groupId AND name = :name LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);
        $this->assertEquals('twin', $member->name);

        // Range conditions
        $query = $this->db('member')->where('group_id BETWEEN ? AND ?', array('1', '2'));
        $this->assertEquals("SELECT * FROM member WHERE group_id BETWEEN ? AND ?", $query->getSql());

        $member = $query->find();
        $this->assertGreaterThanOrEqual(1, $member->group_id);
        $this->assertLessThanOrEqual(2, $member->group_id);

        // Subset conditions
        $query = $this->db('member')->where(array('group_id' => array('1', '2')));
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE group_id IN (?, ?) LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);

        $query = $this->db('member')->where(array(
            'id' => '1',
            'group_id' => array('1', '2')
        ));
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE id = ? AND group_id IN (?, ?) LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->id);

        // Overwrite where
        $query = $this
            ->db('member')
            ->where('id = :id')
            ->where('group_id = :groupId')
            ->setParameter('groupId', 1);
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member WHERE group_id = :groupId LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);

        // Where with empty content
        $query = $this->db('member')->where(array());
        $this->assertEquals("SELECT * FROM member", $query->getSql());

        // Order
        $query = $this->db('member')->orderBy('id', 'ASC');
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member ORDER BY id ASC LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->id);

        // Add order
        $query = $this->db('member')->orderBy('id', 'ASC')->addOrderBy('group_id', 'ASC');
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member ORDER BY id ASC, group_id ASC LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->id);

        // Select
        $query = $this->db('member')->select('id, group_id');
        $member = $query->fetch();

        $this->assertEquals("SELECT id, group_id FROM member LIMIT 1", $query->getSql());
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);

        // Add select
        $query = $this->db('member')->select('id')->addSelect('group_id');
        $member = $query->fetch();

        $this->assertEquals("SELECT id, group_id FROM member LIMIT 1", $query->getSql());
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);

        // Distinct
        $query = $this->db('member')->select('DISTINCT group_id');
        $member = $query->find();

        $this->assertEquals("SELECT DISTINCT group_id FROM member LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);

        // Limit
        $query = $this->db('member')->limit(2);
        $member = $query->findAll();

        $this->assertEquals("SELECT * FROM member LIMIT 2", $query->getSql());
        $this->assertCount(2, $member);

        // Offset
        $query = $this->db('member')->limit(1)->offset(1);
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member LIMIT 1 OFFSET 1", $query->getSql());
        $this->assertEquals(2, $member->id);

        // Page
        $query = $this->db('member')->page(3);
        $this->assertEquals("SELECT * FROM member LIMIT 10 OFFSET 20", $query->getSql());

        // Mixed limit and page
        $query = $this->db('member')->limit(3)->page(3);
        $this->assertEquals("SELECT * FROM member LIMIT 3 OFFSET 6", $query->getSql());

        // Group by
        $query = $this->db('member')->groupBy('id, group_id');
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member GROUP BY id, group_id LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);

        // Having
        $query = $this->db('member')->groupBy('id, group_id')->having('group_id >= ?', '1');
        $member = $query->find();

        $this->assertEquals("SELECT * FROM member GROUP BY id, group_id HAVING group_id >= ? LIMIT 1", $query->getSql());
        $this->assertEquals('1', $member->group_id);

        // Join
        $query = $this
            ->db('member')
            ->select('member.*, member_group.name AS group_name')
            ->leftJoin('member_group', 'member_group.id = member.group_id');
        $member = $query->fetch();

        $this->assertEquals("SELECT member.*, member_group.name AS group_name FROM member LEFT JOIN member_group ON member_group.id = member.group_id LIMIT 1", $query->getSql());
        $this->assertArrayHasKey('group_name', $member);

        // Join with table alias
        $query = $this
            ->db('member u')
            ->rightJoin('member_group g', 'g.id = u.group_id');

        $this->assertEquals("SELECT * FROM member u RIGHT JOIN member_group g ON g.id = u.group_id", $query->getSql());
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

        $this->assertInstanceOf('\Widget\Db\Record', $members['twin']);
        $this->assertInstanceOf('\Widget\Db\Record', $members['test']);

        $members = $members->toArray();

        $this->assertArrayHasKey('twin', $members);
        $this->assertArrayHasKey('test', $members);

        $this->setExpectedException('RuntimeException', 'Index field "test" not found in fetched data');
        $members = $this->db('member')
            ->indexBy('test')
            ->fetchAll();
    }

    public function testQueryUpdate()
    {
        $this->initFixtures();

        $query = $this->db
            ->createQueryBuilder()
            ->update('member')
            ->set('name = ?')
            ->where('id = 1')
            ->setParameter(0, 'twin2');
        $result = $query->execute();
        $member = $this->db->find('member', 1);

        $this->assertEquals("UPDATE member SET name = ? WHERE id = 1", $query->getSql());
        $this->assertEquals(1, $result);
        $this->assertEquals('twin2', $member->name);
    }

    public function testBindValue()
    {
        $this->initFixtures();

        // Not array parameter
        $member = $this->db->fetch("SELECT * FROM member WHERE id = ?", 1, PDO::PARAM_INT);

        $this->assertEquals('1', $member['id']);

        // Array parameter
        $member = $this->db->fetch("SELECT * FROM member WHERE id = ?", array(1), array(PDO::PARAM_INT));

        $this->assertEquals('1', $member['id']);

        $member = $this->db->fetch("SELECT * FROM member WHERE id = ? AND group_id = ?", array(1, 1), array(
            PDO::PARAM_INT // (no parameter type for second placeholder)
        ));

        $this->assertEquals('1', $member['id']);
        $this->assertEquals('1', $member['group_id']);

        // Name parameter
        $member = $this->db->fetch("SELECT * FROM member WHERE id = :id", array(
            'id' => 1
        ), array(
            'id' => PDO::PARAM_INT
        ));

        $this->assertEquals('1', $member['id']);

        // Name parameter with colon
        $member = $this->db->fetch("SELECT * FROM member WHERE id = :id", array(
            'id' => 1
        ), array(
            ':id' => PDO::PARAM_INT
        ));

        $this->assertEquals('1', $member['id']);

        $member = $this->db->fetch("SELECT * FROM member WHERE id = :id", array(
            'id' => '1'
        ));

        $this->assertEquals('1', $member['id']);
    }

    public function testFetchColumn()
    {
        $this->initFixtures();

        $count = $this->db->fetchColumn("SELECT COUNT(id) FROM member");
        $this->assertEquals(2, $count);
    }

    public function testRecordNamespace()
    {
        $this->initFixtures();

        $this->db->setOption('recordNamespace', 'WidgetTest\Db');

        $member = $this->db->find('member', 1);

        $this->assertEquals('WidgetTest\Db\Member', $this->db->getRecordClass('member'));
        $this->assertInstanceOf('WidgetTest\Db\Member', $member);
    }

    public function testCustomRecordClass()
    {
        $this->initFixtures();

        $this->db->setOption('recordClasses', array(
            'member' => 'WidgetTest\Db\Member'
        ));

        $member = $this->db->find('member', 1);

        $this->assertEquals('WidgetTest\Db\Member', $this->db->getRecordClass('member'));
        $this->assertInstanceOf('WidgetTest\Db\Member', $member);
    }

    public function testRecordToArray()
    {
        $this->initFixtures();

        $member = $this->db->find('member', 1)->toArray();

        $this->assertInternalType('array', $member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayHasKey('name', $member);
        $this->assertArrayHasKey('address', $member);

        $member = $this->db->find('member', 1)->toArray(array('id', 'group_id'));
        $this->assertInternalType('array', $member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);
        $this->assertArrayNotHasKey('address', $member);

        $member = $this->db->find('member', 1)->toArray(array('id', 'group_id', 'notExistField'));
        $this->assertInternalType('array', $member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayNotHasKey('name', $member);
        $this->assertArrayNotHasKey('address', $member);

        $member = $this->db->create('member')->toArray();
        $this->assertInternalType('array', $member);
        $this->assertArrayHasKey('id', $member);
        $this->assertArrayHasKey('group_id', $member);
        $this->assertArrayHasKey('name', $member);
        $this->assertArrayHasKey('address', $member);
        $this->assertNull($member['id']);
        $this->assertNull($member['group_id']);
        $this->assertNull($member['name']);
        $this->assertNull($member['address']);

        $this->db->setOption('recordClasses', array(
            'member' => 'WidgetTest\Db\Member'
        ));
    }

    public function testToJson()
    {
        $member = $this->db->create('member');
        $this->assertInternalType('string', $member->toJson());
    }

    public function testDeleteRecord()
    {
        $this->initFixtures();

        $member = $this->db->find('member', 1);

        $result = $member->delete();

        $this->assertTrue($result);

        $member = $this->db->find('member', 1);

        $this->assertFalse($member);
    }

    public function testGetTable()
    {
        $this->initFixtures();

        $member = $this->db->member('1');

        $this->assertEquals('member', $member->getTable());
    }

    public function testFieldNotFound()
    {
        $this->initFixtures();

        $member = $this->db->member('1');

        $this->setExpectedException('\InvalidArgumentException', 'Field "notFound" not found in record class "Widget\Db\Record"');

        $member->notFound;
    }

    public function testCollection()
    {
        $this->initFixtures();

        $members = $this->db->findAll('member');

        $this->assertInstanceOf('\Widget\Db\Collection', $members);

        // ToArray
        $this->assertInternalType('array', $members->toArray());

        // Filter
        $firstGroupMembers = $members->filter(function($member){
            if ('1' == $member->group_id) {
                return true;
            } else {
                return false;
            }
        });

        $this->assertEquals('1', $firstGroupMembers[0]->group_id);
        $this->assertInstanceOf('\Widget\Db\Collection', $firstGroupMembers);
        $this->assertNotSame($members, $firstGroupMembers);

        // Reduce
        $count = $members->reduce(function($count, $member){
            return ++$count;
        });

        $this->assertEquals(2, $count);
    }

    public function testRecordUnset()
    {
        $this->initFixtures();

        $member = $this->db->member('1');

        $this->assertEquals('twin', $member->name);
        $this->assertEquals('1', $member->group_id);

        unset($member->name);
        $member->remove('group_id');

        $this->assertEquals(null, $member->name);
        $this->assertEquals(null, $member->group_id);
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

        $this->db->setOption(array(
            'beforeQuery' => function(){
                echo 'beforeQuery';
            },
            'afterQuery' => function(){
                echo 'afterQuery';
            }
        ));

        $this->db->find('member', 1);
    }

    public function testBeforeAndAfterQueryForUpdate()
    {
        $this->initFixtures();

        $this->expectOutputString('beforeQueryafterQuery');

        $this->db->setOption(array(
            'beforeQuery' => function(){
                echo 'beforeQuery';
            },
            'afterQuery' => function(){
                echo 'afterQuery';
            }
        ));

        $this->db->executeUpdate("UPDATE member SET name = 'twin2' WHERE id = 1");

        $this->assertEquals("UPDATE member SET name = 'twin2' WHERE id = 1", $this->db->getLastSql());
    }

    public function testException()
    {
        $this->setExpectedException('PDOException');

        $this->db->query("SELECT * FROM noThis table");
    }

    public function testUpdateWithoutParameters()
    {
        $this->initFixtures();

        $result = $this->db->executeUpdate("UPDATE member SET name = 'twin2' WHERE id = 1");

        $this->assertEquals(1, $result);
    }

    public function testCount()
    {
        $this->initFixtures();

        $count = $this->db('member')->count();

        $this->assertInternalType('int', $count);
        $this->assertEquals(2, $count);

        $count = $this->db('member')->select('id, name')->limit(1)->offset(2)->count();

        $this->assertInternalType('int', $count);
        $this->assertEquals(2, $count);
    }

    public function testParameters()
    {
        $this->initFixtures();

        $db = $this->db;

        $query = $db('member')
            ->where('id = :id AND group_id = :groupId')
            ->setParameters(array(
                'id' => 1,
                'groupId' => 1
            ), array(
                PDO::PARAM_INT,
                PDO::PARAM_INT
            ));
        $member = $query->find();

        $this->assertEquals(array(
            'id' => 1,
            'groupId' => 1
        ), $query->getParameters());

        $this->assertEquals(1, $query->getParameter('id'));
        $this->assertNull($query->getParameter('no'));

        $this->assertEquals(1, $member->id);
        $this->assertEquals(1, $member->group_id);

        // Set parameter
        $query->setParameter('id', 1, PDO::PARAM_STR);
        $member = $query->find();
        $this->assertEquals(1, $member->id);

        $query->setParameter('id', 10);
        $member = $query->find();
        $this->assertFalse($member);


        $query = $this
            ->db('member')
            ->andWhere('id = ?', '1', PDO::PARAM_INT);

        $member = $query->find();
        $this->assertEquals('1', $member->id);
    }

    /**
     * @dataProvider providerForParameterValue
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
        $this->assertInternalType('array', $array);
    }

    public function providerForParameterValue()
    {
        return array(
            array('0'),
            array(0),
            array(null),
            array(true),
            array(array(null))
        );
    }

    public function testGetAndResetAll()
    {
        $query = $this->db('member')->offset(1)->limit(1);

        $this->assertEquals(1, $query->get('offset'));
        $this->assertEquals(1, $query->get('limit'));

        $queryParts = $query->getAll();
        $this->assertArrayHasKey('offset', $queryParts);
        $this->assertArrayHasKey('limit', $queryParts);

        $query->resetAll();

        $this->assertEquals(null, $query->get('offset'));
        $this->assertEquals(null, $query->get('limit'));
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
        $this->assertEquals(2, $count);

        $count = $db->count('member', array('id' => '1'));
        $this->assertEquals(1, $count);

        $count = $db->count('member', array('id' => '1'));
        $this->assertSame(1, $count);

        $count = $db->count('member', array('id' => '123'));
        $this->assertSame(0, $count);
    }

    public function testTablePrefix()
    {
        $this->initFixtures();

        $db = $this->db;

        $db->setOption('tablePrefix', 'tbl_');
        $this->assertEquals('tbl_member', $db->getTable('member'));

        $db->setOption('tablePrefix', 'post_');
        $this->assertEquals(3, $db->count('tag'));
    }

    public function testConnectFails()
    {
        $this->setExpectedException('\PDOException');
        $test = &$this;
        $db = new \Widget\Db(array(
            'widget' => $this->widget,
            'driver' => 'mysql',
            'host'   => '255.255.255.255',
            'dbname' => 'test',
            'connectFails' => function($db, $exception) use($test) {
                $test->assertTrue(true);
                $test->assertInstanceOf('PDOException', $exception);
            }
        ));
        $db->connect();
    }

    public function testGlobalOption()
    {
        $cb = 'pi';
        $this->widget->setConfig(array(
            'db' => array(
                'beforeConnect' => $cb,
            ),
            'cb.db' => array(
                'db' => $this->db,
                'global' => true
            )
        ));

        $this->assertSame($cb, $this->db->getOption('beforeConnect'));
        $this->assertSame($cb, $this->cbDb->getOption('beforeConnect'));

        // Remove all relation configuration
        unset($this->cbDb);
        $this->widget->remove('cbDb');
        $this->widget->setConfig('cb.db', array(
            'db' => null
        ));
    }

    public function testUnsupportedDriver()
    {
        $this->setExpectedException('\RuntimeException', 'Unsupported database driver: abc');

        $db = new \Widget\Db(array(
            'widget' => $this->widget,
            'driver' => 'abc'
        ));

        $db->query("SELECT MAX(1, 2)");
    }

    public function testCustomDsn()
    {
        $db = new \Widget\Db(array(
            'widget' => $this->widget,
            'dsn' => 'sqlite::memory:'
        ));

        $this->assertEquals('sqlite::memory:', $db->getDsn());
    }

    public function testInsertBatch()
    {
        if ('sqlite' == $this->db->getDriver()) {
            $this->markTestSkipped('batch insert is not supported by SQLite');
        }

        $result = $this->db->insertBatch('member', array(
            array(
                'group_id' => '1',
                'name' => 'twin',
                'address' => 'test'
            ),
            array(
                'group_id' => '1',
                'name' => 'test',
                'address' => 'test'
            )
        ));

        $this->assertEquals(2, $result);
    }

    public function testSlaveDb()
    {
        // Generate slave db configuration name
        $driver = $this->db->getDriver();
        $configName = $driver . 'Slave.db';

        // Set configuration for slave db
        $options = $this->widget->getConfig('db');
        $this->widget->setConfig($configName, $options);

        $this->db->setOption('slaveDb', $configName);

        $query = "SELECT 1 + 2";
        $this->db->query($query);

        // Receives the slave db widget
        /** @var $slaveDb \Widget\Db */
        $slaveDb = $this->widget->get($configName);

        // Test that the query is execute by slave db, not the master db
        $this->assertNotContains($query, $this->db->getQueries());
        $this->assertContains($query, $slaveDb->getQueries());
    }

    public function testReload()
    {
        $this->db->setOption('recordNamespace', 'WidgetTest\Db');
        $this->initFixtures();

        /** @var $member2 \WidgetTest\Db\Member */
        $member = $this->db->find('member', 1);
        /** @var $member2 \WidgetTest\Db\Member */
        $member2 = $this->db->find('member', 1);

        $member->group_id = 2;
        $member->save();

        $this->assertNotEquals($member->group_id, $member2->group_id);
        $this->assertEquals(1, $member->getLoadTimes());

        $member2->reload();
        $this->assertEquals($member->group_id, $member2->group_id);
        $this->assertEquals(2, $member2->getLoadTimes());
    }

    public function testFindOne()
    {
        $this->initFixtures();

        $this->setExpectedException('Exception', 'Record not found', 404);

        $this->db->findOne('member', 999);
    }

    public function testIsModified()
    {
        $this->initFixtures();

        $member = $this->db->create('member');
        $this->assertFalse($member->isModified());

        $member->name = 'tt';
        $member->group_id = '1';
        $member->address = 'address';
        $this->assertFalse($member->isModified('id'));
        $this->assertTrue($member->isModified('name'));
        $this->assertTrue($member->isModified());
        $this->assertNull($member->getOldData('name'));

        $member->name = 'aa';
        $this->assertTrue($member->isModified());
        $this->assertEquals('tt', $member->getOldData('name'));

        $member->save();
        $this->assertFalse($member->isModified());
        $this->assertEmpty($member->getOldData());
    }
}