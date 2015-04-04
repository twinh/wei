<?php

namespace WeiTest;

class BlockTest extends TestCase
{
    public function testBlock()
    {
        $block = wei()->block;

        $block('text');
        echo 'some text content';
        $block->end();

        $this->assertEquals('some text content', $block->get('text'));
    }

    public function testAppend()
    {
        $block = wei()->block;

        $block->start('text');
        echo 'some text content';
        $block->end();

        $block->start('text', 'append');
        echo ' add more';
        $block->end();

        $this->assertEquals('some text content add more', $block->get('text'));
    }

    public function testPrepend()
    {
        $block = wei()->block;

        $block->start('text');
        echo 'some text content';
        $block->end();

        $block->start('text', 'prepend');
        echo 'There is ';
        $block->end();

        $this->assertEquals('There is some text content', $block->get('text'));
    }

    public function testSet()
    {
        $block = wei()->block;

        $block->start('text');
        echo 'some text content';
        $block->end();

        $block->start('text', 'set');
        echo 'New content';
        $block->end();

        $this->assertEquals('New content', $block->get('text'));
    }

    public function testUnsupportedBlockType()
    {
        $this->setExpectedException('Exception', 'Unsupported block type "dododo"');

        wei()->block('text', 'dododo');
    }
}