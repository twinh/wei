<?php

namespace WidgetTest;


class GravatarTest extends TestCase
{
    public function testInvoke()
    {
        $url = $this->gravatar('test@example.com');
        $this->assertEquals('http://www.gravatar.com/avatar/55502f40dc8b7c769880b10874abc9d0?s=80&d=mm', $url);
    }

    public function testSecure()
    {
        $this->gravatar->setOption('secure', true);
        $url = $this->gravatar('test@example.com');
        $this->assertEquals('https://secure.gravatar.com/avatar/55502f40dc8b7c769880b10874abc9d0?s=80&d=mm', $url);
    }

    public function testDefaultImage()
    {
        $this->gravatar->setDefault('monsterid');
        $url = $this->gravatar('test@example.com');
        $this->assertEquals('http://www.gravatar.com/avatar/55502f40dc8b7c769880b10874abc9d0?s=80&d=monsterid', $url);
    }

    public function testSize()
    {
        $url = $this->gravatar->large('test@example.com');
        $largeSize = $this->gravatar->getOption('largeSize');
        $this->assertEquals('http://www.gravatar.com/avatar/55502f40dc8b7c769880b10874abc9d0?s=' . $largeSize . '&d=mm', $url);

        $url = $this->gravatar->small('test@example.com');
        $smallSize = $this->gravatar->getOption('smallSize');
        $this->assertEquals('http://www.gravatar.com/avatar/55502f40dc8b7c769880b10874abc9d0?s=' . $smallSize . '&d=mm', $url);
    }

    public function testRating()
    {
        $url = $this->gravatar('test@example.com', null, null, 'g');

        $this->assertEquals('http://www.gravatar.com/avatar/55502f40dc8b7c769880b10874abc9d0?s=80&d=mm&r=g', $url);
    }
}