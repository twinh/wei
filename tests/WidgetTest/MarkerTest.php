<?php
namespace WidgetTest;

class MarkerTest extends TestCase
{
    public function testInvoker() {
        $marker = $this->object;

        $marker('Testing');

        $data = $marker->getMarkers();

        $this->assertArrayHasKey('Testing', $data);
    }

    public function testDisplay() {
        $marker = $this->object;

        $marker('test1');
        $marker('test2');
        
        // test output records
        $result = $marker->display(false);
        
        $data = $marker->getMarkers();

        $this->assertCount(1 + count($data) + 1, explode('<tr>', $result), '1 + count($data) + 1(explode) rows');

        // test output directly
        ob_start();

        $result = $marker->display();

        ob_end_clean();

        $this->assertInstanceOf('\Widget\Marker', $result);
    }
}