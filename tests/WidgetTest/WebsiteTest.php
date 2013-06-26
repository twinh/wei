<?php

namespace WidgetTest;

class WebsiteTest extends TestCase
{
    public function testWebsite()
    {
        // Set pre-defind configuration
        $this->website->set('title', 'My Blog');
        
        $this->assertEquals('My Blog', $this->website->get('title'));
        $this->assertEquals('My Blog', $this->website('title'));
        
        // Set configuration on demand
        $this->website->set('author', 'twin');
        $this->assertEquals('twin', $this->website->get('author'));
    }
}