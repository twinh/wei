<?php

namespace WeiTest;

/**
 * @property \Wei\View $view
 */
class ViewTest extends TestCase
{
    /**
     * @var \Wei\View
     */
    protected $object;

    public function setUp()
    {
        parent::setUp();

        $this->object->setDirs(__DIR__ . '/Fixtures/views');
    }

    public function testInvoker()
    {
        $view = $this->object;

        // Render by invoker
        $content = $view('layout.php', array(
            'content' => __METHOD__
        ));
        $this->assertContains(__METHOD__, $content);
    }

    public function testAssign()
    {
        $view = $this->object;
        $value = __METHOD__;

        $view->assign('content', $value);
        $content = $view->render('layout.php');

        $this->assertContains($value, $content);
    }

    public function testAssignArray()
    {
        $view = $this->object;

        $view->assign(array(
            'key' => 'value',
            'key2' => 'value2',
        ));

        $this->assertEquals('value', $view->get('key'));
        $this->assertEquals('value2', $view->get('key2'));
        $this->assertNull($view->get('not-defined-key'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFileNotFoundException()
    {
        $this->object->render('not-found-file');
    }

    public function testLayout()
    {
        $content = $this->object->render('content.php');

        $this->assertContains('layout start', $content);
        $this->assertContains('layout end', $content);
    }

    public function testDisplay()
    {
        ob_start();
        $this->object->display('layout.php', array('content' => 'test'));
        $content = ob_get_clean();

        $expected = $this->object->render('layout.php', array('content' => 'test'));

        $this->assertEquals($expected, $content);
    }

    public function testGetExtension()
    {
        $this->assertEquals('.php', $this->object->getExtension());
    }

    public function testGetVarWei()
    {
        $this->assertEquals($this->wei, $this->object->get('wei'));
        $this->assertEquals($this->wei->view, $this->object->get('view'));
    }

    public function testArrayAccess()
    {
        $view = $this->view;

        $view['name'] = 'value';
        $this->assertEquals('value', $view['name']);

        $view['name'] = 'value2';
        $this->assertEquals('value2', $view['name']);

        unset($view['name']);

        $this->assertFalse(isset($view['name']));

        // $view['name'] will cause key exists again
        $this->assertNull($view['name']);

        $this->assertTrue(isset($view['name']));

        $view['a']['b'] = 'c';
        $this->assertEquals('c', $view['a']['b']);

        $view['d']['e']['f']['g'] = 'h';
        $this->assertEquals('h', $view['d']['e']['f']['g']);

        $view['i'] = array();
        $view['i']['j'] = 'k';
        $this->assertEquals('k', $view['i']['j']);
    }

    public function testReferenceArrayAccess()
    {
        $view = $this->view;

        $view['items'] = array();
        $view['items'][] = 'item 1';
        $view['items'][] = 'item 2';

        $this->assertEquals('item 1', $view['items'][0]);
        $this->assertEquals('item 2', $view['items'][1]);
    }

    public function testEmptyDir()
    {
        $this->view->setDirs('');
        $testFile = 'tests/unit/ViewTest.php';
        $fullFile = $this->view->getFile($testFile);

        $this->assertEquals($fullFile, $testFile);
    }

    public function testKeywordVarsOverwrite()
    {
        $this->view->assign(array(
            'data' => 'data'
        ));

        // No error occur. Fatal error: Unsupported operand types in xxx
        $content = $this->object->render('content.php');

        $this->assertContains('layout start', $content);
        $this->assertContains('layout end', $content);
    }

    public function testAssignViewVarInViewFile()
    {
        $content = $this->view->render('assignViewVarInViewFile/content.php');
        $this->assertContains('assign!', $content);
    }
}
