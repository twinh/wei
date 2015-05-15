<?php

namespace WeiTest;

class TagCacheTest extends TestCase
{
    public function testOneTag()
    {
        $cache = wei()->cache;

        $cache->set('1', 'The content of first post');

        $postCache = wei()->tagCache('posts');

        $postCache->set('1', 'This is the first post.');

        $this->assertEquals('This is the first post.', $postCache->get('1'));

        $this->assertEquals('The content of first post', $cache->get('1'));

        $postCache->clear();
    }

    public function testTwoTags()
    {
        $cache = wei()->cache;

        $cache->set('1', 'The content of first post');

        $puCache = wei()->tagCache('posts', 'users');
        $puCache->get('x');
        $postCache = wei()->tagCache('posts');

        $postCache->remove('1');
        $puCache->set('1', 'This is the first post, from admin.');

        $this->assertEquals('This is the first post, from admin.', $puCache->get('1'));

        $this->assertEquals('The content of first post', $cache->get('1'));

        $this->assertFalse($postCache->get('1'));

        $puCache->clear();
        $postCache->clear();
    }

    public function testClear()
    {
        $cache = wei()->cache;
        $cache->set('1', 'The content of first post');

        $postCache = wei()->tagCache('posts');
        $postCache->set('1', 'This is the first post');
        $postCache->clear();

        $this->assertFalse($postCache->get('1'));

        // Clear tag caches have no effect with other caches
        $this->assertEquals('The content of first post', $cache->get('1'));

        $postCache->clear();
    }

    public function testClearTag()
    {
        $cache = wei()->cache;

        $puCache = wei()->tagCache(array('posts', 'users'));
        $puCache->set('1-1', 'This is the first post, from admin');

        $postCache = wei()->tagCache('posts');
        $postCache->set('1', 'This is the first post');

        $userCache = wei()->tagCache('users');
        $userCache->set('1', 'admin');

        $this->assertEquals('This is the first post, from admin', $puCache->get('1-1'));
        $this->assertEquals('This is the first post', $postCache->get('1'));

        $userCache->clear();

        $this->assertFalse($userCache->get('1'));
        $this->assertFalse($puCache->get('1-1'));
        $this->assertEquals('This is the first post', $postCache->get('1'));

        $puCache->clear();
        $postCache->clear();
    }
}