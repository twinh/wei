<?php

namespace WeiTest;

use Wei\Share;

/**
 * @internal
 */
final class ShareTest extends TestCase
{
    public function testTitle()
    {
        $this->assertNull(Share::getTitle());

        Share::setTitle('title');

        $this->assertSame('title', Share::getTitle());
    }

    public function testUrl()
    {
        $this->assertNull(Share::getUrl());

        Share::setUrl('url');

        $this->assertSame('url', Share::getUrl());
    }

    public function testDescription()
    {
        $this->assertNull(Share::getDescription());

        Share::setDescription('description');

        $this->assertSame('description', Share::getDescription());
    }

    public function testImage()
    {
        $this->assertNull(Share::getImage());

        Share::setImage('image');

        $this->assertSame('image', Share::getImage());
    }

    public function testToJson()
    {
        Share::setTitle('title')
            ->setUrl('url')
            ->setDescription('description')
            ->setImage('image');

        $this->assertSame('{"title":"title","image":"image","description":"description","url":"url"}', Share::toJson());
    }

    public function testToWechatJson()
    {
        Share::setTitle('title')
            ->setUrl('url')
            ->setDescription('description')
            ->setImage('image');

        $this->assertSame(
            '{"title":"title","desc":"description","link":"url","imgUrl":"image"}',
            Share::toWechatJson()
        );
    }
}
