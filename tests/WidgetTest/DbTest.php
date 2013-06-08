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

        // Not found and create new object
        $user = $this->db->findOrCreate('user', '3', array(
            'name' => 'name'
        ));
        $this->assertEquals('name', $user->name);
        $this->assertEquals('3', $user->id);

        // Found
        $user = $this->db->findOrCreate('user', '2');

        $this->assertEquals('2', $user->id);
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

    public function testQueryFetch()
    {
        $data = $this->db('user')->where('id = 1')->fetch();
        $this->assertInternalType('array', $data);
        $this->assertEquals('1', $data['id']);
    }

    public function testQueryFetchAll()
    {
        $data = $this->db('user')->fetchAll();

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

        $this->assertEquals("SELECT * FROM user WHERE name = 'twin' LIMIT 1", $query->getSql());
        $this->assertEquals('twin', $user->name);

        // ? conditions
        $query = $this->db('user')->where('name = ?', 'twin');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE name = ? LIMIT 1", $query->getSql());
        $this->assertEquals('twin', $user->name);

        $query = $this->db('user')->where('group_id = ? AND name = ?', array('1', 'twin'));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = ? AND name = ? LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->group_id);
        $this->assertEquals('twin', $user->name);

        // : conditions
        $query = $this->db('user')->where('group_id = :groupId AND name = :name', array(
            'groupId' => '1',
            'name' => 'twin'
        ));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = :groupId AND name = :name LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->group_id);
        $this->assertEquals('twin', $user->name);

        $query = $this->db('user')->where('group_id = :groupId AND name = :name', array(
            ':groupId' => '1',
            ':name' => 'twin'
        ));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = :groupId AND name = :name LIMIT 1", $query->getSql());
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

        $this->assertEquals("SELECT * FROM user WHERE group_id IN (?, ?) LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        $query = $this->db('user')->where(array(
            'id' => '1',
            'group_id' => array('1', '2')
        ));
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE id = ? AND group_id IN (?, ?) LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->id);

        // Overwrite where
        $query = $this
            ->db('user')
            ->where('id = :id')
            ->where('group_id = :groupId')
            ->setParameter('groupId', 1);
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user WHERE group_id = :groupId LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        // Order
        $query = $this->db('user')->orderBy('id', 'ASC');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user ORDER BY id ASC LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->id);

        // Add order
        $query = $this->db('user')->orderBy('id', 'ASC')->addOrderBy('group_id', 'ASC');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user ORDER BY id ASC, group_id ASC LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->id);

        // Select
        $query = $this->db('user')->select('id, group_id');
        $user = $query->find()->toArray();

        $this->assertEquals("SELECT id, group_id FROM user LIMIT 1", $query->getSql());
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayNotHasKey('name', $user);

        // Add select
        $query = $this->db('user')->select('id')->addSelect('group_id');
        $user = $query->find()->toArray();

        $this->assertEquals("SELECT id, group_id FROM user LIMIT 1", $query->getSql());
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayNotHasKey('name', $user);

        // Distinct
        $query = $this->db('user')->select('DISTINCT group_id');
        $user = $query->find();

        $this->assertEquals("SELECT DISTINCT group_id FROM user LIMIT 1", $query->getSql());
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

        $this->assertEquals("SELECT * FROM user GROUP BY group_id LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        // Having
        $query = $this->db('user')->groupBy('group_id')->having('group_id >= ?', '1');
        $user = $query->find();

        $this->assertEquals("SELECT * FROM user GROUP BY group_id HAVING group_id >= ? LIMIT 1", $query->getSql());
        $this->assertEquals('1', $user->group_id);

        // Join
        $query = $this
            ->db('user')
            ->select('user.*, user_group.name AS group_name')
            ->leftJoin('user_group', 'user_group.id = user.group_id');
        $user = $query->find()->toArray();

        $this->assertEquals("SELECT user.*, user_group.name AS group_name FROM user LEFT JOIN user_group ON user_group.id = user.group_id LIMIT 1", $query->getSql());
        $this->assertArrayHasKey('group_name', $user);

        // Join with table alias
        $query = $this
            ->db('user', 'u')
            ->rightJoin('user_group g', 'g.id = u.group_id');

        $this->assertEquals("SELECT * FROM user u RIGHT JOIN user_group g ON g.id = u.group_id", $query->getSql());

        /*$query1 = $this
            ->db('user')
            ->groupBy('id')
            ->having('group_id >= ?', '1')
            ->where('id >= ?', '2');

        $query2 = $this
            ->db('user')
            ->groupBy('id')
            ->where('id >= ?', '2')
            ->having('group_id >= ?', '1');*/
    }

    public function testQueryUpdate()
    {
        $query = $this->db
            ->createQueryBuilder()
            ->update('user')
            ->set('name = ?')
            ->where('id = 1')
            ->setParameter(0, 'twin2');
        $result = $query->execute();
        $user = $this->db->find('user', 1);

        $this->assertEquals("UPDATE user SET name = ? WHERE id = 1", $query->getSql());
        $this->assertEquals(1, $result);
        $this->assertEquals('twin2', $user->name);
    }

        public function testBindValue()
    {
        // Not array parameter
        $user = $this->db->fetch("SELECT * FROM user WHERE id = ?", 1, PDO::PARAM_INT);

        $this->assertEquals('1', $user['id']);

        // Array parameter
        $user = $this->db->fetch("SELECT * FROM user WHERE id = ?", array(1), array(PDO::PARAM_INT));

        $this->assertEquals('1', $user['id']);

        $user = $this->db->fetch("SELECT * FROM user WHERE id = ? AND group_id = ?", array(1, 1), array(
            PDO::PARAM_INT // (no parameter type for second placeholder)
        ));

        $this->assertEquals('1', $user['id']);
        $this->assertEquals('1', $user['group_id']);

        // Name parameter
        $user = $this->db->fetch("SELECT * FROM user WHERE id = :id", array(
            'id' => 1
        ), array(
            'id' => PDO::PARAM_INT
        ));

        $this->assertEquals('1', $user['id']);

        // Name parameter with colon
        $user = $this->db->fetch("SELECT * FROM user WHERE id = :id", array(
            'id' => 1
        ), array(
            ':id' => PDO::PARAM_INT
        ));

        $this->assertEquals('1', $user['id']);

        $user = $this->db->fetch("SELECT * FROM user WHERE id = :id", array(
            'id' => '1'
        ));

        $this->assertEquals('1', $user['id']);
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


        $this->db->setOption('recordClasses', array(
            'user' => 'WidgetTest\DbTest\User'
        ));

        $user = $this->db->find('user', 1);
        $posts = $user->posts;
        $data = $user->toArray();

        $this->assertInternalType('array', $data);
        $this->assertInternalType('array', $data['posts']);
        $this->assertInternalType('array', $data['posts'][0]);
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

    public function testCollection()
    {
        $users = $this->db->findAll('user');

        $this->assertInstanceOf('\Widget\Db\Collection', $users);

        // ToArray
        $this->assertInternalType('array', $users->toArray());

        // Filter
        $firstGroupUsers = $users->filter(function($user){
            if ('1' == $user->group_id) {
                return true;
            } else {
                return false;
            }
        });

        $this->assertEquals('1', $firstGroupUsers[0]->group_id);
        $this->assertInstanceOf('\Widget\Db\Collection', $firstGroupUsers);
        $this->assertNotSame($users, $firstGroupUsers);

        // Reduce
        $count = $users->reduce(function($count, $user){
            return ++$count;
        });

        $this->assertEquals(2, $count);
    }

    public function testRecordUnset()
    {
        $user = $this->db->user('1');

        $this->assertEquals('twin', $user->name);
        $this->assertEquals('1', $user->group_id);

        unset($user->name);
        $user->remove('group_id');

        $this->assertEquals(null, $user->name);
        $this->assertEquals(null, $user->group_id);
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
        $this->expectOutputString('beforeQueryafterQuery');

        $this->db->setOption(array(
            'beforeQuery' => function(){
                echo 'beforeQuery';
            },
            'afterQuery' => function(){
                echo 'afterQuery';
            }
        ));

        $this->db->find('user', 1);
    }

    public function testBeforeAndAfterQueryForUpdate()
    {
        $this->expectOutputString('beforeQueryafterQuery');

        $this->db->setOption(array(
            'beforeQuery' => function(){
                echo 'beforeQuery';
            },
            'afterQuery' => function(){
                echo 'afterQuery';
            }
        ));

        $this->db->executeUpdate("UPDATE user SET name = 'twin2' WHERE id = 1");
    }

    public function testException()
    {
        $this->setExpectedException('RuntimeException');

        $this->db->query("SELECT * FROM noThis table");
    }

    public function testUpdateWithoutParameters()
    {
        $result = $this->db->executeUpdate("UPDATE user SET name = 'twin2' WHERE id = 1");

        $this->assertEquals(1, $result);
    }

    public function testFindHelper()
    {
        $user = $this->db->user->find('1');

        $this->assertEquals('1', $user->id);
    }

    public function testCount()
    {
        $count = $this->db('user')->count();

        $this->assertInternalType('int', $count);
        $this->assertEquals(2, $count);

        $count = $this->db('user')->select('id, name')->limit(1)->offset(2)->count();

        $this->assertInternalType('int', $count);
        $this->assertEquals(2, $count);
    }

    public function testParameters()
    {
        $db = $this->db;

        $query = $db
            ->from('user')
            ->where('id = :id AND group_id = :groupId')
            ->setParameters(array(
                'id' => 1,
                'groupId' => 1
            ), array(
                PDO::PARAM_INT,
                PDO::PARAM_INT
            ));
        $user = $query->find();

        $this->assertEquals(array(
            'id' => 1,
            'groupId' => 1
        ), $query->getParameters());

        $this->assertEquals(1, $query->getParameter('id'));
        $this->assertNull($query->getParameter('no'));

        $this->assertEquals(1, $user->id);
        $this->assertEquals(1, $user->group_id);

        // Set parameter
        $query->setParameter('id', 1, PDO::PARAM_STR);
        $user = $query->find();
        $this->assertEquals(1, $user->id);

        $query->setParameter('id', 10);
        $user = $query->find();
        $this->assertFalse($user);
    }

    public function testGetAndResetAll()
    {
        $query = $this->db('user')->offset(1)->limit(1);

        $this->assertEquals(1, $query->get('offset'));
        $this->assertEquals(1, $query->get('limit'));

        $queryParts = $query->getAll();
        $this->assertArrayHasKey('offset', $queryParts);
        $this->assertArrayHasKey('limit', $query);

        $query->resetAll();

        $this->assertEquals(null, $query->get('offset'));
        $this->assertEquals(null, $query->get('limit'));
    }
}