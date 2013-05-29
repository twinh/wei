<?php

namespace WidgetTest;

/**
 * @property \Widget\Db db
 */
class DbTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists(('\Doctrine\DBAL\DriverManager'))) {
            $this->markTestSkipped('doctrine\dbal is required');
            return;
        }

        parent::setUp();

        /* @var $db \Doctrine\DBAL\Connection */
        $db = $this->dbal();

        $db->query("CREATE TABLE groups (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE users (id INTEGER NOT NULL, group_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE posts (id INTEGER NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE tags (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $db->query("CREATE TABLE posts_tags (post_id INTEGER NOT NULL, tag_id INTEGER NOT NULL)");

        $db->insert('groups', array(
            'id' => '1',
            'name' => 'vip'
        ));

        $db->insert('users', array(
            'group_id' => '1',
            'name' => 'twin',
            'address' => 'test'
        ));

        $db->insert('users', array(
            'group_id' => '1',
            'name' => 'test',
            'address' => 'test'
        ));

        $db->insert('posts', array(
            'user_id' => '1',
            'name' => 'my first post',
        ));

        $db->insert('posts', array(
            'user_id' => '1',
            'name' => 'my second post',
        ));

        $db->insert('tags', array(
            'id' => '1',
            'name' => 'database'
        ));

        $db->insert('tags', array(
            'id' => '2',
            'name' => 'PHP'
        ));

        $db->insert('posts_tags', array(
            'post_id' => '1',
            'tag_id' => '1',
        ));

        $db->insert('posts_tags', array(
            'post_id' => '1',
            'tag_id' => '2',
        ));

        $db->insert('posts_tags', array(
            'post_id' => '2',
            'tag_id' => '1',
        ));
    }

    public function testGetTable()
    {
        $this->assertInstanceOf('\Widget\Table', $this->db->getTable('users'));

        $this->assertInstanceOf('\Widget\Table', $this->db->users);
    }

    public function testGetRecordByPk()
    {
        $db = $this->db;

        $user = $db->users('1');

        $this->assertInstanceOf('\Widget\Table', $user);

        $this->assertEquals('1', $user->id);
        $this->assertEquals('twin', $user->name);
        $this->assertEquals('test', $user->address);
        $this->assertEquals('1', $user->groupId);

        // Relation one-to-one
        $post = $user->post;

        $this->assertInstanceOf('\Widget\Table', $post);

        $this->assertEquals('1', $post->id);
        $this->assertEquals('my first post', $post->name);
        $this->assertEquals('1', $post->userId);

        // Relation belong-to
        $group = $user->group;

        $this->assertInstanceOf('\Widget\Table', $group);

        $this->assertEquals('1', $group->id);
        $this->assertEquals('vip', $group->name);

        // Relation one-to-many
        $posts = $user->posts;

        $this->assertInstanceOf('\Widget\Coll', $posts);

        $firstPost = $posts[0];
        $this->assertInstanceOf('\Widget\Table', $firstPost);

        $this->assertEquals('1', $firstPost->id);
        $this->assertEquals('my first post', $firstPost->name);
        $this->assertEquals('1', $firstPost->userId);
    }
}