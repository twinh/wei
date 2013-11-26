<?php

namespace WeiTest;

use Wei\Wei;

class WeiTest extends TestCase
{
    public function createUserWei()
    {
        return new \WeiTest\Fixtures\User(array(
            'wei' => $this->wei,
            'name' => 'twin'
        ));
    }

    public function testConfig()
    {
        $wei = $this->wei;
        $wei->setConfig(array(
            'test' => array(
                'a' => array(
                    'b' => array(
                        'c' => false
                    )
                )
            )
        ));

        $this->assertSame(array('a' => array('b' => array('c' => false))), $wei->getConfig('test'));
        $this->assertSame(array('b' => array('c' => false)), $wei->getConfig('test:a'));
        $this->assertSame(array('c' => false), $wei->getConfig('test:a:b'));
        $this->assertSame(false, $wei->getConfig('test:a:b:c'));
        $this->assertSame(null, $wei->getConfig('test:noThisKey'));
        $this->assertSame(false, $wei->getConfig('test:noThisKey', false));
        $this->assertSame(0, $wei->getConfig('test:noThisKey', 0));

        $wei->setConfig('test2', array());
        $this->assertSame(array(), $wei->getConfig('test2'));

        $wei->setConfig('test2:a', 'b');
        $this->assertSame('b', $wei->getConfig('test2:a'));
        $this->assertSame(array('a' => 'b'), $wei->getConfig('test2'));

        $wei->setConfig('test2:a:b', 'c');
        $this->assertSame('c', $wei->getConfig('test2:a:b'));

        $wei->setConfig('test.request', array());
        $providers = $wei->getOption('providers');
        $this->assertArrayHasKey('testRequest', $providers);
        $this->assertSame('test.request', $providers['testRequest']);
    }

    public function testMergeConfig()
    {
        $wei = $this->wei;

        $wei->setConfig(array(
            'weiName' => array(
                'option1' => 'value1',
            )
        ));

        $wei->setConfig(array(
            'weiName' => array(
                'option2' => 'value2',
            )
        ));

        $this->assertEquals('value1', $wei->getConfig('weiName:option1'));
        $this->assertEquals('value2', $wei->getConfig('weiName:option2'));

        $wei->setConfig(array(
            'weiName' => array(
                'option1' => 'value3',
            )
        ));

        $this->assertEquals('value3', $wei->getConfig('weiName:option1'));
        $this->assertEquals('value2', $wei->getConfig('weiName:option2'));
    }

    public function testSetOptionInSetConfig()
    {
        $wei = $this->wei;
        $request = $wei->request;

        $wei->setConfig('request', array(
            'method' => 'POST'
        ));
        $this->assertEquals('POST', $request->getMethod());

        // No effect at this time
        $wei->setConfig('request/method', 'PUT');
        $this->assertEquals('POST', $request->getMethod());

        $wei->setConfig(array(
            'request' => array(
                'method' => 'DELETE'
            )
        ));
        $this->assertEquals('DELETE', $request->getMethod());
    }

    public function testGetConfigs()
    {
        $configs = $this->wei->getConfig();
        $this->assertInternalType('array', $configs);
    }

    public function testInvalidArgumentExceptionForWeiOption()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Option "wei" of class "Wei\Request" should be an instance of "Wei\Wei"',
            1000
        );
        new \Wei\Request(array(
            'wei' => new \stdClass
        ));
    }

    public function testGetOption()
    {
        $user = $this->createUserWei();

        $this->assertEquals('twin', $user->getName());

        $this->assertEquals('twin', $user->getOption('name'));
    }

    public function testSetInis()
    {
        $wei = $this->wei;

        $wei->setInis(array(
            'date.timezone' => 'Asia/Shanghai'
        ));

        $this->assertEquals('Asia/Shanghai', ini_get('date.timezone'));
        $this->assertIsSubset(array('date.timezone' => 'Asia/Shanghai'), $wei->getOption('inis'));

        $wei->setInis(array(
            'date.default_latitude' => '31.7667'
        ));
        $this->assertIsSubset(array(
            'date.timezone' => 'Asia/Shanghai',
            'date.default_latitude' => '31.7667'
        ), $wei->getOption('inis'));
    }

    public function testSet()
    {
        $request = new \Wei\Request(array(
            'wei' => $this->wei,
        ));

        $this->wei->set('request', $request);
        $this->assertSame($request, $this->wei->request);
    }

    public function testSetWithCaseSensitiveName()
    {
        $arrayCache = new \Wei\ArrayCache(array(
            'wei' => $this->wei
        ));

        $this->wei->set('myArrayCache', $arrayCache);

        $this->assertSame($arrayCache, $this->wei->get('myArrayCache'));
    }

    public function testInstanceNotFoundWeiFromWeiManager()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Property or object "notFoundWei" (class "Wei\NotFoundWei") not found, called in file',
            1012
        );
        $this->wei->notFoundWei;
    }

    public function testInstanceNotFoundWei()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Property or object "notFoundWei" (class "Wei\NotFoundWei") not found, called in file',
            1012
        );
        $this->wei->request->notFoundWei;
    }

    public function testInvokeNotFoundWei()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Method "Wei\Wei->notFoundWei" or object "notFoundWei" (class "Wei\NotFoundWei") not found, called in file',
            1011
        );
        $this->wei->notFoundWei();
    }

    public function testInvokeNotFoundWeiByCallUserFunc()
    {
        $this->setExpectedException(
            'BadMethodCallException',
            'Property or method "notFoundWei" not found',
            1013
        );
        $this->wei->get('notFoundWei');
    }

    public function testGetFromproviders()
    {
        // Set options for sub request
        $this->wei->setConfig('sub.request', array(
            'fromGlobal' => false,
            'data' => array(
                'id' => 'fromSubRequest'
            )
        ));

        $request = $this->wei->request;
        $request->set('id', 'fromOrigin');

        $serviceWithProvider = new \WeiTest\Fixtures\ServiceWithProvider(array(
            'wei' => $this->wei
        ));

        // Instance request wei from 'request.sub' configuration
        $subRequest = $serviceWithProvider->request;
        $this->assertEquals('fromSubRequest', $subRequest->get('id'));

        $this->assertEquals('fromOrigin', $request->get('id'));
    }

    public function testProviders()
    {
        $wei = $this->wei;

        $wei->setOption('providers', array(
            'subRequest' => 'sub.request'
        ));

        $this->assertInstanceOf('Wei\Request', $wei->subRequest);
        $this->assertNotSame($wei->request, $wei->subRequest);

        $subRequest = $wei->request->subRequest;
        $this->assertInstanceOf('Wei\Request', $subRequest);
        unset($wei->request->subRequest);
    }

    public function testPreloadWeiInDependenceMap()
    {
        $wei = new Wei(array(
            'wei' => array(
                'providers' => array(
                    'request' => 'sub.request',
                ),
                'preload' => array(
                    'request'
                )
            ),
            'request' => array(
                'fromGlobal' => true,
            ),
            'sub.request' => array(
                'fromGlobal' => false,
            )
        ));

        $this->assertFalse($wei->request->getOption('fromGlobal'));
    }

    public function testInvoke()
    {
        $this->request->set('id', __METHOD__);

        // Equals to $this->wei->request('id')
        $id = $this->wei->invoke('request', array('id'));

        $this->assertEquals(__METHOD__, $id);
    }

    public function testInstanceWeiWithWeiOption()
    {
        $wei = new \Wei\Wei(array(
            'wei' => array(
                'autoload' => false,
            ),
        ));

        $this->assertFalse($wei->getOption('autoload'));
    }

    public function testNewInstance()
    {
        $newRequest = $this->wei->newInstance('request');

        $this->assertInstanceOf('\Wei\Request', $newRequest);
        $this->assertEquals($this->request, $newRequest);
        $this->assertNotSame($this->request, $newRequest);
    }

    public function testGetClassFromAliases()
    {
        $this->wei->setAlias('request', '\Wei\Request');

        $this->assertEquals('\Wei\Request', $this->wei->getClass('request'));
    }

    public function testRemove()
    {
        // Instance request wei
        $request = $this->wei->request;

        $this->assertTrue($this->wei->remove('request'));
        $this->assertFalse($this->wei->remove('request'));

        // Re-instance request wei
        $this->assertNotSame($request, $this->wei->request);
    }

    public function testHas()
    {
        $this->wei->request;

        $this->assertEquals('\Wei\Request', $this->wei->has('request'));
        $this->assertFalse($this->wei->has('request2'));
    }

    public function testAutoload()
    {
        $this->wei->setAutoload(false);
        $this->assertNotContains(array($this->wei, 'autoload'), spl_autoload_functions());

        $this->wei->setAutoload(true);
        $this->assertContains(array($this->wei, 'autoload'), spl_autoload_functions());

        $this->wei->setAutoloadMap(array(
            'WeiTest' => dirname(__DIR__)
        ));

        $this->assertTrue(class_exists('WeiTest\Fixtures\Autoload'));
        $this->assertFalse(class_exists('WeiTest\Fixtures\AutoloadNotFound'));
    }

    public function testImport()
    {
        $wei = new Wei(array(
            'wei' => array(
                'autoload' => false,
                'import' => array(
                    array(
                        'dir' => __DIR__ . '/Fixtures/Import',
                        'namespace' => '\WeiTest\Fixtures\Import',
                        'format' => 'test%s',
                        'autoload' => true
                    )
                )
            )
        ));

        $this->assertEquals('\WeiTest\Fixtures\Import\Wei1', $wei->has('testWei1'));
        $this->assertEquals('\WeiTest\Fixtures\Import\Wei2', $wei->has('testWei2'));
        $this->assertFalse($this->wei->has('testWei3'));
    }

    public function testImportInvalidDir()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Fail to import classes from non-exists directory',
            1014
        );
        $this->wei->import(__DIR__ . '/not found/', 'test');
    }

    public function testImportWithAutoload()
    {
        $wei = new Wei(array(
            'wei' => array(
                'autoload' => false,
                'import' => array(
                    array(
                        'dir' => __DIR__ . '/Fixtures/Import',
                        'namespace' => '\WeiTest\Fixtures\Import',
                        'format' => 'test%s',
                        'autoload' => true
                    )
                )
            )
        ));

        $map = $wei->getOption('autoloadMap');
        $this->assertArrayHasKey('\WeiTest\Fixtures\Import', $map);
    }

    public function testFileAsConfiguration()
    {
        $wei = Wei::getContainer(__DIR__ . '/Fixtures/env/twin.php');

        $this->assertTrue($wei->getConfig('twin'));
    }

    public function testInvalidArgumentExceptionOnCreate()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Configuration should be array or file',
            1010
        );
        Wei::getContainer(new \stdClass);
    }

    public function testInvalidArgumentExceptionWhenFileNotFind()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Configuration should be array or file',
            1010
        );
        Wei::getContainer('not existing file');
    }

    public function testSetContainer()
    {
        $wei = Wei::getContainer();
        $newWei = new Wei();

        Wei::setContainer($newWei);
        $this->assertSame($newWei, Wei::getContainer());

        // reset
        Wei::setContainer($wei);
    }

    public function testWeiFunction()
    {
        $this->assertSame(Wei::getContainer(), wei());
    }

    public function testWidgetFunction()
    {
        $this->assertSame(Wei::getContainer(), widget());
    }

    public function testGetSelf()
    {
        $this->assertSame($this->wei, $this->wei->wei);
        $this->assertSame($this->wei, $this->wei->wei->wei);
    }

    public function testDebug()
    {
        $this->wei->setDebug(false);
        $this->assertFalse($this->wei->isDebug());

        $this->wei->setDebug(true);
        $this->assertTrue($this->wei->isDebug());
    }

    public function testSetAlias()
    {
        $this->wei->setAlias('weiName', 'className');
        $aliases = $this->wei->getOption('aliases');

        $this->assertArrayHasKey('weiName', $aliases);
        $this->assertEquals('className', $aliases['weiName']);
    }

    public function testIsInstanced()
    {
        $this->assertTrue($this->wei->isInstanced('wei'));
        $this->wei->remove('weiName');
        $this->assertFalse($this->wei->isInstanced('weiName'));
    }

    public function testConstructCallback()
    {
        $beforeConstruct = $afterConstruct = array();
        $wei = new Wei(array(
            'wei' => array(
                'autoload' => false,
                'beforeConstruct' => function($wei, $fullName, $name) use(&$beforeConstruct) {
                    $beforeConstruct[] = $fullName;
                },
                'afterConstruct' => function($wei, $fullName, $name, $object) use(&$afterConstruct) {
                    $afterConstruct[] = $fullName;
                }
            )
        ));

        // Instance weis
        $wei->request;
        $wei->{'sub.request'};

        $this->assertContains('request', $beforeConstruct);
        $this->assertContains('sub.request', $afterConstruct);
        $this->assertContains('request', $beforeConstruct);
        $this->assertContains('sub.request', $afterConstruct);
    }

    public function testCreateNewContainer()
    {
        $origContainer = wei();
        Wei::setContainer(null);
        $container = Wei::getContainer();

        $this->assertNotSame($origContainer, $container);

        // Reset container
        Wei::setContainer($origContainer);
    }
}
