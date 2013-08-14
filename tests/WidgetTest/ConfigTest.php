<?php

namespace WidgetTest;

class ConfigTest extends TestCase
{
    public function testWebsite()
    {
        $this->config->set('title', 'My Blog');

        $this->assertEquals('My Blog', $this->config->get('title'));
        $this->assertEquals('My Blog', $this->config('title'));

        // Set configuration on demand
        $this->config->set('author', 'twin');
        $this->assertEquals('twin', $this->config->get('author'));
    }
}