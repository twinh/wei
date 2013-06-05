<?php

namespace WidgetTest;

use Widget\DB\QueryBuilder;

/**
 * @property \Widget\Db db
 * @method \Widget\Db\QueryBuilder db($table)
 */
class DbTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $db = $this->db;

        $db->query("CREATE TABLE user_group (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE user (id INTEGER NOT NULL, group_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE post (id INTEGER NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE tag (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE post_tag (post_id INTEGER NOT NULL, tag_id INTEGER NOT NULL)");

        $db->insert('user_group', array(
            'id' => '1',
            'name' => 'vip'
        ));

        $db->insert('user', array(
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test'
        ));

        $db->insert('user', array(
            'group_id' => '1',
            'name' => 'test',
            'address' => 'test'
        ));

        $db->insert('post', array(
            'user_id' => '1',
            'name' => 'my first post',
        ));

        $db->insert('post', array(
            'user_id' => '1',
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

        $this->assertTrue($db->isConnected());

        $db->close();

        $this->assertFalse($db->isConnected());
    }

    public function testGetRecord()
    {
        $this->assertInstanceOf('\Widget\Db\Record', $this->db->create('user'));

        $this->assertInstanceOf('\Widget\Db\Record', $this->db->user);
    }

    public function testRelation()
    {
        $db = $this->db;

        $db->setOption('recordNamespace', 'WidgetTest\DbTest');

        /** @var $user \WidgetTest\DbTest\User */
        $user = $db->user('1');

        $this->assertInstanceOf('\Widget\Db\Record', $user);

        $this->assertEquals('1', $user->id);
        $this->assertEquals('twin', $user->name);
        $this->assertEquals('test', $user->address);
        $this->assertEquals('1', $user->group_id);

        // Relation one-to-one
        $post = $user->post;

        $this->assertInstanceOf('\Widget\Db\Record', $post);

        $this->assertEquals('1', $post->id);
        $this->assertEquals('my first post', $post->name);
        $this->assertEquals('1', $post->user_id);

        // Relation belong-to
        $group = $user->group;

        $this->assertInstanceOf('\Widget\Db\Record', $group);

        $this->assertEquals('1', $group->id);
        $this->assertEquals('vip', $group->name);

        // Relation one-to-many
        $posts = $user->posts;

        $this->assertInstanceOf('\Widget\Db\Collection', $posts);

        $firstPost = $posts[0];
        $this->assertInstanceOf('\Widget\Db\Record', $firstPost);

        $this->assertEquals('1', $firstPost->id);
        $this->assertEquals('my first post', $firstPost->name);
        $this->assertEquals('1', $firstPost->user_id);
    }

    public function testSet()
    {
        $user = $this->db->user('1');

        $this->assertEquals('1', $user->id);

        $user->id = 2;

        $this->assertEquals('2', $user->id);
    }

    public function testDynamicRelation()
    {
        $db = $this->db;

        $user = $db->user('1');

        $post = $user->post = $db->find('post', array('user_id' => $user->id));

        $this->assertInstanceOf('\Widget\Db\Record', $post);

        $this->assertEquals('1', $post->id);
        $this->assertEquals('my first post', $post->name);
        $this->assertEquals('1', $post->user_id);
    }

    public function testUpdate()
    {
        $this->db->update('user', array('name' => 'hello'), array('id' => '1'));

        $user = $this->db->find('user', '1');

        $this->assertEquals('hello', $user->name);
    }

    public function testDelete()
    {
        $this->db->delete('user', array('id' => '1'));

        $user = $this->db->find('user', 1);

        $this->assertFalse($user);
    }

    public function testFind()
    {
        $user = $this->db->find('user', '1');

        $this->assertEquals('1', $user->id);
    }

    public function testFindOrCreate()
    {
        $user = $this->db->find('user', '3');
        $this->assertFalse($user);

        $user = $this->db->findOrCreate('user', '3', array(
            'name' => 'name'
        ));
        $this->assertEquals('name', $user->name);
        $this->assertEquals('3', $user->id);
    }

    public function testRecordSave()
    {
        $db = $this->db;

        // Existing user
        $user = $db->user('1');
        $result = $user->save();

        $this->assertTrue($result);
        $this->assertEquals('1', $user->id);

        // New user save with data
        $user = $db->user;
        $result = $user->save(array(
            'group_id' => '1',
            'name' => 'save',
            'address' => 'save'
        ));

        $this->assertTrue($result);
        $this->assertEquals('3', $user->id);
        $this->assertEquals('save', $user->name);

        // Save again
        $result = $user->save();
        $this->assertTrue($result);
        $this->assertEquals('3', $user->id);
    }

    public function testSelect()
    {
        $data = $this->db->select('user', 1);

        $this->assertEquals('twin', $data['name']);
    }

    public function testSelectColumn()
    {
        $data = $this->db->select('user', 1, 'id, name');

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayNotHasKey('group_id', $data);
    }

    public function testSelectAll()
    {
        $data = $this->db->selectAll('user', array('name' => 'twin'));

        $this->assertCount(1, $data);

        $data = $this->db->selectAll('user');

        $this->assertCount(2, $data);
    }

    public function testFetch()
    {
        $data = $this->db->fetch("SELECT * FROM user WHERE name = ?", 'twin');
        $this->assertInternalType('array', $data);
        $this->assertEquals('twin', $data['name']);

        $data = $this->db->fetch("SELECT * FROM user WHERE name = ?", 'notFound');
        $this->assertFalse($data);

        $data = $this->db->fetch("SELECT * FROM user WHERE name = :name", array('name' => 'twin'));
        $this->assertInternalType('array', $data);
        $this->assertEquals('twin', $data['name']);

        $data = $this->db->fetch("SELECT * FROM user WHERE name = :name", array(':name' => 'twin'));
        $this->assertInternalType('array', $data);
        $this->assertEquals('twin', $data['name']);
    }

    public function testFetchAll()
    {
        $data = $this->db->fetchAll("SELECT * FROM user WHERE group_id = ?", '1');

        $this->assertInternalType('array', $data);
        $this->assertEquals('1', $data[0]['group_id']);
    }

    public function testGetRecordClass()
    {
        $db = $this->db;

        $db->setOption('recordNamespace', 'WidgetTest\DbTest');

        $this->assertEquals('WidgetTest\DbTest\User', $db->getRecordClass('user'));
        $this->assertEquals('WidgetTest\DbTest\User', $db->getRecordClass('User'));
        $this->assertEquals('WidgetTest\DbTest\UserGroup', $db->getRecordClass('user_group'));
        $this->assertEquals('WidgetTest\DbTest\UserGroup', $db->getRecordClass('UserGroup'));
        $this->assertEquals('WidgetTest\DbTest\UserGroup', $db->getRecordClass('User_Group'));
    }

    /**
     * @link http://edgeguides.rubyonrails.org/active_record_querying.html#conditions
     */
    public function testQuery()
    {
        // Pure string conditions
        $query = $this->db('user')->where("name = 'twin'");
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE name = 'twin'", $query->getSql());
        $this->assertEquals('twin', $user->name);

        // ? conditions
        $query = $this->db('user')->where('name = ?', 'twin');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE name = ?", $query->getSql());
        $this->assertEquals('twin', $user->name);

        $query = $this->db('user')->where('group_id = ? AND name = ?', array('1', 'twin'));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = ? AND name = ?", $query->getSql());
        $this->assertEquals('1', $user->group_id);
        $this->assertEquals('twin', $user->name);

        // : conditions
        $query = $this->db('user')->where('group_id = :groupId AND name = :name', array(
            'groupId' => '1',
            'name' => 'twin'
        ));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = :groupId AND name = :name", $query->getSql());
        $this->assertEquals('1', $user->group_id);
        $this->assertEquals('twin', $user->name);

        $query = $this->db('user')->where('group_id = :groupId AND name = :name', array(
            ':groupId' => '1',
            ':name' => 'twin'
        ));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = :groupId AND name = :name", $query->getSql());
        $this->assertEquals('1', $user->group_id);
        $this->assertEquals('twin', $user->name);

        // Range conditions
        $query = $this->db('user')->where('group_id BETWEEN ? AND ?', array('1', '2'));
        $this->assertEquals("SELECT * FROM user WHERE group_id BETWEEN ? AND ?", $query->getSql());

        $user = $query->find();
        $this->assertGreaterThanOrEqual(1, $user->group_id);
        $this->assertLessThanOrEqual(2, $user->group_id);

        // Subset conditions
        $query = $this->db('user')->where(array('group_id' => array('1', '2')));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id IN (?, ?)", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        $query = $this->db('user')->where(array(
            'id' => '1',
            'group_id' => array('1', '2')
        ));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE id = ? AND group_id IN (?, ?)", $query->getSql());
        $this->assertEquals('1', $user->id);

        // Order
        $query = $this->db('user')->orderBy('id', 'ASC');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user ORDER BY id ASC", $query->getSql());
        $this->assertEquals('1', $user->id);

        // Add order
        $query = $this->db('user')->orderBy('id', 'ASC')->addOrderBy('group_id', 'ASC');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user ORDER BY id ASC, group_id ASC", $query->getSql());
        $this->assertEquals('1', $user->id);

        // Select
        $query = $this->db('user')->select('id, group_id');
        $user = $query->find()->toArray();

        $this->assertEquals("SELECT id, group_id FROM user", $query->getSql());
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayNotHasKey('name', $user);

        // Add select
        $query = $this->db('user')->select('id')->addSelect('group_id');
        $user = $query->find()->toArray();

        $this->assertEquals("SELECT id, group_id FROM user", $query->getSql());
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayNotHasKey('name', $user);

        // Distinct
        $query = $this->db('user')->select('DISTINCT group_id');
        $user = $query->find();

        $this->assertEquals("SELECT DISTINCT group_id FROM user", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        // Limit
        $query = $this->db('user')->limit(2);
        $user = $query->findAll();

        $this->assertEquals("SELECT * FROM user LIMIT 2", $query->getSql());
        $this->assertCount(2, $user);

        // Offset
        $query = $this->db('user')->limit(1)->offset(1);
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user LIMIT 1 OFFSET 1", $query->getSql());
        $this->assertEquals(2, $user->id);

        // Page
        $query = $this->db('user')->page(3);
        $this->assertEquals("SELECT * FROM user LIMIT 10 OFFSET 20", $query->getSql());

        // Mixed limit and page
        $query = $this->db('user')->limit(3)->page(3);
        $this->assertEquals("SELECT * FROM user LIMIT 3 OFFSET 6", $query->getSql());

        // Group by
        $query = $this->db('user')->groupBy('group_id');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user GROUP BY group_id", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        // Having
        $query = $this->db('user')->groupBy('group_id')->having('group_id >= ?', '1');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user GROUP BY group_id HAVING group_id >= ?", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        // Join
        //$query = $this->db('user')->leftJoin('grouop AS group', 'group.id = user.group_id');
        //$user = $query->find();
        //$query = $this->db('user')->leftJoin('group')
    }

    public function testFetchColumn()
    {
        $count = $this->db->fetchColumn("SELECT COUNT(id) FROM user");
        $this->assertEquals(2, $count);
    }

    public function testRecordNamespace()
    {
        $this->db->setOption('recordNamespace', 'WidgetTest\DbTest');

        $user = $this->db->find('user', 1);

        $this->assertEquals('WidgetTest\DbTest\User', $this->db->getRecordClass('user'));
        $this->assertInstanceOf('WidgetTest\DbTest\User', $user);
    }

    public function testCustomRecordClass()
    {
        $this->db->setOption('recordClasses', array(
            'user' => 'WidgetTest\DbTest\User'
        ));

        $user = $this->db->find('user', 1);

        $this->assertEquals('WidgetTest\DbTest\User', $this->db->getRecordClass('user'));
        $this->assertInstanceOf('WidgetTest\DbTest\User', $user);
    }

    public function testRecordToArray()
    {
        $user = $this->db->find('user', 1)->toArray();

        $this->assertInternalType('array', $user);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('address', $user);

        $user = $this->db->find('user', 1)->toArray(array('id', 'group_id'));
        $this->assertInternalType('array', $user);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayNotHasKey('name', $user);
        $this->assertArrayNotHasKey('address', $user);
    }

    public function testDeleteRecord()
    {
        $user = $this->db->find('user', 1);

        $result = $user->delete();

        $this->assertTrue($result);

        $user = $this->db->find('user', 1);

        $this->assertFalse($user);
    }

    public function testGetTable()
    {
        $user = $this->db->user('1');

        $this->assertEquals('user', $user->getTable());
    }

    public function testColumnNotFound()
    {
        $user = $this->db->user('1');

        $this->setExpectedException('\InvalidArgumentException', 'Column or relation "notFound" not found in record class "Widget\Db\Record"');

        $user->notFound;
    }
}