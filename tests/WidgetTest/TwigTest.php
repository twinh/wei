<?php

namespace WidgetTest;

class TwigTest extends TestCase
{
    public function testInvoker()
    {
        $this->assertInstanceOf('\Twig_Environment', $this->twig());
    }
}