<?php

namespace WidgetTest;

class RedirectTest extends TestCase
{
    public function testLocation()
    {
        $this->redirect;
    }

    public function testCustomView()
    {
        $this->expectOutputString('redirect');

        $this->redirect->setView(__DIR__ . '/Fixtures/redirect.php');

        $this->redirect();
    }
    /**
     * @expectedException \RuntimeException
     */
    public function testViewNotFound()
    {
        $this->redirect->setView('not found');
    }

    public function testRedirectByHeader()
    {
        $this->expectOutputRegex('/http:\/\/www\.google\.com/');

        $this->redirect('http://www.google.com');

        $this->assertEquals('http://www.google.com', $this->redirect->getHeader('Location'));
    }

    public function testWait()
    {
        $this->expectOutputRegex('/content=\"5;url=http:\/\/www\.google\.com"/');

        $this->redirect->setWait(5);

        $this->redirect('http://www.google.com');
    }
}