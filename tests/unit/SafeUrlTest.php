<?php

namespace WeiTest;

/**
 * @internal
 */
final class SafeUrlTest extends TestCase
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
        $this->assertArrayHasKey('signature', $queries);

        $this->object->req
            ->setQuery('timestamp', $queries['timestamp'])
            ->setQuery('userId', $queries['userId'])
            ->setQuery('signature', $queries['signature']);

        $this->assertTrue($this->object->verify());

        // Ignore other parameters
        $this->object->req->setQuery('ttt', 'sss');
        $this->assertTrue($this->object->verify());
    }
}
