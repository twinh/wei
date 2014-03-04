<?php

namespace WeiTest;

class SafeUrlTest extends TestCase
{
    /**
     * @var \Wei\SafeUrl
     */
    protected $object;

    public function testGenerate()
    {
        $this->object->setParams('userId');
        $url = $this->object->generate('http://example.com/?userId=123');

        $parts = parse_url($url);
        parse_str($parts['query'], $queries);

        $this->assertEquals('http://example.com', $parts['scheme'] . '://' . $parts['host']);

        $this->assertArrayHasKey('userId', $queries);
        $this->assertArrayHasKey('timestamp', $queries);
        $this->assertArrayHasKey('flag', $queries);

        $this->object->request
            ->setQuery('timestamp', $queries['timestamp'])
            ->setQuery('userId', $queries['userId'])
            ->setQuery('flag', $queries['flag']);


        $this->assertTrue($this->object->verify());

        // Ignore other parameters
        $this->object->request->setQuery('ttt', 'sss');
        $this->assertTrue($this->object->verify());
    }
}