<?php

namespace WeiTest;

class TaggedCacheTest extends TestCase
{
    public function testOneTag()
    {
        $cache = wei()->cache;

        $cache->set('posts:1', 'The content of first post');

        $postCache = $cache->tags('posts');

        $postCache->set('posts:1', 'This is the first post.');

        $this->assertEquals('This is the first post.', $postCache->get('posts:1'));

        $this->assertEquals('The content of first post', $cache->get('posts:1'));
    }

    public function testTwoTags()
    {
        $cache = wei()->cache;

        $cache->set('posts:1', 'The content of first post');

        $puCache = $cache->tags('posts', 'users');
        $postCache = $cache->tags('post');

        $postCache->remove('posts:1');
        $puCache->set('posts:1', 'This is the first post, from admin.');

        $this->assertEquals('This is the first post, from admin.', $puCache->get('posts:1'));

        $this->assertEquals('The content of first post', $cache->get('posts:1'));

        $this->assertFalse($postCache->get('posts:1'));
    }
}