<?php

namespace WidgetTest;

class DownloadTest extends TestCase
{
    public function testDownload()
    {
        ob_start();
        $this->download(__DIR__ . '/Fixtures/view.php');
        $content = ob_get_clean();

        $this->assertContains('<?php $this->layout(\'layout.php\') ?>', $content);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFileNotFoundException()
    {
        $this->download('not found file');
    }
}