<?php

namespace WeiTest;

use Wei\Req;
use Wei\Ret;
use Wei\Wei;
use WeiTest\Fixtures\StaticService;

/**
 * @internal
 */
final class WeiTest extends TestCase
{
    public function createUserService()
    {
        return new \WeiTest\Fixtures\TestUser([
            'wei' => $this->wei,
            'name' => 'twin',
        ]);
    }

    public function testConfig()
    {
        $wei = $this->wei;
        $wei->setConfig([
            'test' => [
                'a' => [
                    'b' => [
                        'c' => false,
                    ],
                ],
            ],
        ]);

        $this->assertSame(['a' => ['b' => ['c' => false]]], $wei->getConfig('test'));
        $this->assertSame(['b' => ['c' => false]], $wei->getConfig('test.a'));
        $this->assertSame(['c' => false], $wei->getConfig('test.a.b'));
        $this->assertFalse($wei->getConfig('test.a.b.c'));
        $this->assertNull($wei->getConfig('test.noThisKey'));
        $this->assertFalse($wei->getConfig('test.noThisKey', false));
        $this->assertSame(0, $wei->getConfig('test.noThisKey', 0));

        $wei->setConfig('test2', []);
        $this->assertSame([], $wei->getConfig('test2'));

        $wei->setConfig('test2.a', 'b');
        $this->assertSame('b', $wei->getConfig('test2.a'));
        $this->assertSame(['a' => 'b'], $wei->getConfig('test2'));

        $wei->setConfig('test2.a.b', 'c');
        $this->assertSame('c', $wei->getConfig('test2.a.b'));

        $wei->setConfig('test:request', []);
        $providers = $wei->getOption('providers');
        $this->assertArrayHasKey('testRequest', $providers);
        $this->assertSame('test:request', $providers['testRequest']);
    }

    public function testMergeConfig()
    {
        $wei = $this->wei;

        $wei->setConfig([
            'weiName' => [
                'option1' => 'value1',
            ],
        ]);

        $wei->setConfig([
            'weiName' => [
                'option2' => 'value2',
            ],
        ]);

        $this->assertEquals('value1', $wei->getConfig('weiName.option1'));
        $this->assertEquals('value2', $wei->getConfig('weiName.option2'));

        $wei->setConfig([
            'weiName' => [
                'option1' => 'value3',
            ],
        ]);

        $this->assertEquals('value3', $wei->getConfig('weiName.option1'));
        $this->assertEquals('value2', $wei->getConfig('weiName.option2'));
    }

    public function testSetOptionInSetConfig()
    {
        $wei = $this->wei;
        $request = $wei->req;

        $wei->setConfig('req', [
            'method' => 'POST',
        ]);
        $this->assertEquals('POST', $request->getMethod());

        // No effect at this time
        $wei->setConfig('req/method', 'PUT');
        $this->assertEquals('POST', $request->getMethod());

        $wei->setConfig([
            'req' => [
                'method' => 'DELETE',
            ],
        ]);
        $this->assertEquals('DELETE', $request->getMethod());

        // Reset config
        $wei->setConfig([
            'req' => [
                'method' => '',
            ],
        ]);
    }

    public function testGetConfigs()
    {
        $configs = $this->wei->getConfig();
        $this->assertIsArray($configs);
    }

    public function testRemoveConfig()
    {
        $wei = $this->wei;

        $wei->setConfig('testWei.config', true);
        $wei->removeConfig('testWei.config');

        $this->assertNull($wei->getConfig('testWei.config'));
        $this->assertIsArray($wei->getConfig('testWei'));

        $wei->setConfig('testWei.config.next', 'value');
        $wei->removeConfig('testWei.config.next');
        $this->assertIsArray($wei->getConfig('testWei.config'));

        $wei->removeConfig('testWei.config');
        $this->assertNull($wei->getConfig('testWei.config'));
        $this->assertIsArray($wei->getConfig('testWei'));

        $wei->removeConfig('testWei');
        $this->assertNull($wei->getConfig('testWei'));
    }

    public function testInvalidArgumentExceptionForWeiOption()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Option "wei" of class "Wei\Req" should be an instance of "Wei\Wei"',
            1000
        );
        new \Wei\Req([
            'wei' => new \stdClass(),
        ]);
    }

    public function testGetOption()
    {
        $user = $this->createUserService();

        $this->assertEquals('twin', $user->getName());

        $this->assertEquals('twin', $user->getOption('name'));
    }

    public function testSetInis()
    {
        $wei = $this->wei;

        $wei->setInis([
            'date.timezone' => 'Asia/Shanghai',
        ]);

        $this->assertEquals('Asia/Shanghai', date_default_timezone_get());
        $this->assertIsSubset(['date.timezone' => 'Asia/Shanghai'], $wei->getOption('inis'));

        $wei->setInis([
            'date.default_latitude' => '31.7667',
        ]);
        $this->assertIsSubset([
            'date.timezone' => 'Asia/Shanghai',
            'date.default_latitude' => '31.7667',
        ], $wei->getOption('inis'));
    }

    public function testSet()
    {
        $request = new \Wei\Req([
            'wei' => $this->wei,
        ]);

        $this->wei->set('req', $request);
        $this->assertSame($request, $this->wei->req);
    }

    public function testSetWithCaseSensitiveName()
    {
        $arrayCache = new \Wei\ArrayCache([
            'wei' => $this->wei,
        ]);

        $this->wei->set('myArrayCache', $arrayCache);

        $this->assertSame($arrayCache, $this->wei->get('myArrayCache'));
    }

    public function testInstanceNotFoundServiceFromContainer()
    {
        $this->expectExceptionObject(new \BadMethodCallException(implode('', [
            'Property or object "notFoundService" (class "Wei\NotFoundService") not found, ',
            'called in file "' . __FILE__ . '" at line ' . (__LINE__ + 3),
        ]), 1012));

        $this->wei->notFoundService;
    }

    public function testInstanceNotFoundServiceFromService()
    {
        $this->expectExceptionObject(new \BadMethodCallException(implode('', [
            'Property or object "notFoundService" (class "Wei\NotFoundService") not found, ',
            'called in file "' . __FILE__ . '" at line ' . (__LINE__ + 3),
        ]), 1012));

        $this->wei->req->notFoundService;
    }

    public function testInvokeNotFoundServiceFromContainer()
    {
        $this->expectExceptionObject(new \BadMethodCallException(implode('', [
            'Method "Wei\Wei->notFoundService" (class "Wei\NotFoundService") not found, ',
            'called in file "' . __FILE__ . '" at line ' . (__LINE__ + 3),
        ]), 1014));

        $this->wei->notFoundService();
    }

    public function testInvokeNotFoundServiceFromService()
    {
        $this->expectExceptionObject(new \BadMethodCallException(implode('', [
            'Method "Wei\Req->notFoundService" (class "Wei\NotFoundService") not found, ',
            'called in file "' . __FILE__ . '" at line ' . (__LINE__ + 3),
        ]), 1014));

        $this->wei->req->notFoundService();
    }

    public function testInvokeNotFoundServiceByCallUserFuncFromContainer()
    {
        $this->expectExceptionObject(new \BadMethodCallException(implode('', [
            'Method "Wei\Wei->notFoundService" (class "Wei\NotFoundService") not found, ',
            'called in file "' . __FILE__ . '" at line ' . (__LINE__ + 3),
        ]), 1014));

        call_user_func([$this->wei, 'notFoundService']);
    }

    public function testInvokeNotFoundServiceByCallUserFuncFromService()
    {
        $this->expectExceptionObject(new \BadMethodCallException(implode('', [
            'Method "Wei\Req->notFoundService" (class "Wei\NotFoundService") not found, ',
            'called in file "' . __FILE__ . '" at line ' . (__LINE__ + 3),
        ]), 1014));

        call_user_func([$this->wei->req, 'notFoundService']);
    }

    public function testInvokeNotFoundServiceByGetMethod()
    {
        $this->expectExceptionObject(new \BadMethodCallException('Property or method "notFoundWei" not found', 1013));

        $this->wei->get('notFoundWei');
    }

    public function testGetFromProviders()
    {
        // Set options for sub request
        $this->wei->setConfig('sub:req', [
            'fromGlobal' => false,
            'data' => [
                'id' => 'fromSubRequest',
            ],
        ]);

        $request = $this->wei->req;
        $request->set('id', 'fromOrigin');

        $serviceWithProvider = new \WeiTest\Fixtures\ServiceWithProvider([
            'wei' => $this->wei,
        ]);

        // Instance request wei from 'request.sub' configuration
        $subRequest = $serviceWithProvider->req;
        $this->assertEquals('fromSubRequest', $subRequest->get('id'));

        $this->assertEquals('fromOrigin', $request->get('id'));
    }

    public function testProviders()
    {
        $wei = $this->wei;

        $wei->setOption('providers', [
            'subRequest' => 'sub:request',
        ]);

        $this->assertInstanceOf('Wei\Req', $wei->subRequest);
        $this->assertNotSame($wei->req, $wei->subRequest);

        $subRequest = $wei->req->subRequest;
        $this->assertInstanceOf('Wei\Req', $subRequest);
        unset($wei->req->subRequest);
    }

    public function testPreloadWeiInDependenceMap()
    {
        $wei = new Wei([
            'wei' => [
                'providers' => [
                    'req' => 'sub:req',
                ],
                'preload' => [
                    'req',
                ],
            ],
            'req' => [
                'fromGlobal' => true,
            ],
            'sub:req' => [
                'fromGlobal' => false,
            ],
        ]);

        $this->assertFalse($wei->req->getOption('fromGlobal'));
    }

    public function testInvoke()
    {
        $this->req->set('id', __METHOD__);

        // Equals to $this->wei->req('id')
        $id = $this->wei->invoke('request', ['id']);

        $this->assertEquals(__METHOD__, $id);
    }

    public function testInstanceWeiWithWeiOption()
    {
        $wei = new \Wei\Wei([
            'wei' => [
                'autoload' => false,
            ],
        ]);

        $this->assertFalse($wei->getOption('autoload'));
    }

    public function testNewInstance()
    {
        $newRequest = $this->wei->newInstance('req');

        $this->assertInstanceOf('\Wei\Req', $newRequest);
        $this->assertEquals($this->req, $newRequest);
        $this->assertNotSame($this->req, $newRequest);
    }

    public function testNewInstanceHaveSameConfig()
    {
        $this->wei->setConfig('req', [
            'method' => 'DELETE',
        ]);

        $this->assertSame('DELETE', $this->wei->req->getMethod());

        /** @var Req $newRequest */
        $newRequest = $this->wei->newInstance('req');
        $this->assertSame('DELETE', $newRequest->getMethod());

        // Reset config
        $this->wei->setConfig('req', [
            'method' => '',
        ]);
    }

    public function testGetClassFromAliases()
    {
        $this->wei->setAlias('request', '\Wei\Req');

        $this->assertEquals('\Wei\Req', $this->wei->getClass('request'));
    }

    public function testRemove()
    {
        // Instance request wei
        $request = $this->wei->req;

        $this->assertTrue($this->wei->remove('req'));
        $this->assertFalse($this->wei->remove('req'));

        // Re-instance request wei
        $this->assertNotSame($request, $this->wei->req);
    }

    public function testHas()
    {
        $this->wei->req;

        $this->assertEquals('\Wei\Req', $this->wei->has('request'));
        $this->assertFalse($this->wei->has('request2'));
    }

    public function testAutoload()
    {
        $this->wei->setAutoload(false);
        $this->assertNotContains([$this->wei, 'autoload'], spl_autoload_functions());

        $this->wei->setAutoload(true);
        $this->assertContains([$this->wei, 'autoload'], spl_autoload_functions());

        $this->wei->setAutoloadMap([
            '\WeiTest' => __DIR__,
        ]);

        $this->assertTrue(class_exists('WeiTest\Fixtures\Autoload'));
        $this->assertFalse(class_exists('WeiTest\Fixtures\AutoloadNotFound'));
    }

    public function testImport()
    {
        $wei = new Wei([
            'wei' => [
                'autoload' => false,
                'import' => [
                    [
                        'dir' => __DIR__ . '/Fixtures/Import',
                        'namespace' => '\WeiTest\Fixtures\Import',
                        'format' => 'test%s',
                        'autoload' => true,
                    ],
                ],
            ],
        ]);

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
        $wei = new Wei([
            'wei' => [
                'autoload' => false,
                'import' => [
                    [
                        'dir' => __DIR__ . '/Fixtures/Import',
                        'namespace' => '\WeiTest\Fixtures\Import',
                        'format' => 'test%s',
                        'autoload' => true,
                    ],
                ],
            ],
        ]);

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
        Wei::getContainer(new \stdClass());
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
        $beforeConstruct = $afterConstruct = [];
        $wei = new Wei([
            'wei' => [
                'autoload' => false,
                'beforeConstruct' => function ($wei, $fullName, $name) use (&$beforeConstruct) {
                    $beforeConstruct[] = $fullName;
                },
                'afterConstruct' => function ($wei, $fullName, $name, $object) use (&$afterConstruct) {
                    $afterConstruct[] = $fullName;
                },
            ],
        ]);

        // service instances
        $wei->req;
        $wei->{'sub:req'};

        $this->assertContains('req', $beforeConstruct);
        $this->assertContains('sub:req', $afterConstruct);
        $this->assertContains('req', $beforeConstruct);
        $this->assertContains('sub:req', $afterConstruct);
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

    public function testAutoloadMapDirNotFoundException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Directory "abc" for autoloading is not found'
        );
        $this->wei->setAutoloadMap([
            'namespace' => 'abc',
        ]);
    }

    public function testSetCustomService()
    {
        $this->wei->set('customService', new \stdClass());
        $this->assertInstanceOf('stdClass', $this->wei->get('customService'));

        $this->wei->customService2 = new \stdClass();
        $this->assertInstanceOf('stdClass', $this->wei->customService2);
    }

    public function testNamespace()
    {
        $this->wei->setNamespace('app1');
        $this->assertEquals('app1', $this->wei->getNamespace());
    }

    public function testCustomServiceNamespace()
    {
        $this->wei->setNamespace('app1');
        $this->wei->setConfig('namespace:request', [
            'namespace' => 'test',
        ]);
        $req = $this->wei->get('namespace:request');
        $this->assertNotEquals('app1', $req->getOption('namespace'));
        $this->assertEquals('test', $req->getOption('namespace'));
    }

    public function testStaticCall()
    {
        $wei = Wei::setConfig('staticCall', 'test');
        $this->assertInstanceOf(Wei::class, $wei);

        $value = Wei::getConfig('staticCall', 'test');
        $this->assertSame('test', $value);
    }

    public function testStaticCallWithCheckServiceMethod()
    {
        $wei = $this->wei;
        $wei->setCheckServiceMethod(true);

        $service = StaticService::staticHasTag();
        $this->assertInstanceOf(StaticService::class, $service);

        $hasException = false;
        try {
            // @phpstan-ignore-next-line 方法没有 @svc 标签，会被检测为不存在
            StaticService::staticDontHaveTag();
        } catch (\BadMethodCallException $e) {
            $hasException = true;
            $this->assertSame('Service method "staticDontHaveTag" not found', $e->getMessage());
        }
        $this->assertTrue($hasException);

        $wei->setCheckServiceMethod(false);
    }

    public function testStaticCallWithoutCheckServiceMethod()
    {
        $wei = $this->wei;

        $result = StaticService::staticHasTag();
        $this->assertInstanceOf(StaticService::class, $result);

        /** @phpstan-ignore-next-line 方法没有 @svc 标签，会被检测为不存在 */
        $result = StaticService::staticDontHaveTag();
        $this->assertInstanceOf(StaticService::class, $result);
    }

    public function testDynamicCallWithCheckServiceMethod()
    {
        $wei = $this->wei;
        $wei->setCheckServiceMethod(true);

        $result = StaticService::staticHasTag()->dynamicHasTag();
        $this->assertSame('dynamicHasTag', $result);

        $hasException = false;
        try {
            StaticService::staticHasTag()->dynamicDontHaveTag();
        } catch (\BadMethodCallException $e) {
            $hasException = true;
            $this->assertStringContainsString(implode('', [
                'Method "WeiTest\Fixtures\StaticService->dynamicDontHaveTag" ',
                '(class "Wei\DynamicDontHaveTag") not found, called in file ',
            ]), $e->getMessage());
        }
        $this->assertTrue($hasException);

        $wei->setCheckServiceMethod(false);
    }

    public function testDynamicCallWithoutCheckServiceMethod()
    {
        $result = StaticService::staticHasTag()->dynamicHasTag();
        $this->assertSame('dynamicHasTag', $result);

        $result = StaticService::staticHasTag()->dynamicDontHaveTag();
        $this->assertSame('dynamicDontHaveTag', $result);
    }

    public function testCreateNewInstanceFromDynamicCall()
    {
        $this->wei->setAlias('staticService', StaticService::class);

        $service1 = $this->wei->get('staticService');
        $service2 = $this->wei->get('staticService');
        $this->assertSame($service1, $service2);

        StaticService::enableCreateNewInstance();
        // Remove cached service
        $this->wei->remove('staticService');

        $service1 = $this->wei->get('staticService');
        $service2 = $this->wei->get('staticService');
        $this->assertEquals($service1, $service2);
        $this->assertNotSame($service1, $service2);

        StaticService::disableCreateNewInstance();
    }

    public function testCreateNewInstanceFromStaticCall()
    {
        $this->wei->setAlias('staticService', StaticService::class);

        $service1 = StaticService::staticHasTag();
        $service2 = StaticService::staticHasTag();
        $this->assertSame($service1, $service2);

        StaticService::enableCreateNewInstance();
        // Remove cached service
        $this->wei->remove('staticService');

        $service1 = StaticService::staticHasTag();
        $service2 = StaticService::staticHasTag();
        $this->assertEquals($service1, $service2);
        $this->assertNotSame($service1, $service2);

        StaticService::disableCreateNewInstance();
    }

    public function testCreateNewInstanceWontBeCached()
    {
        $this->wei->setAlias('staticService', StaticService::class);
        StaticService::enableCreateNewInstance();

        // Won't cache `staticService` service
        $this->wei->staticService;

        $service1 = StaticService::staticHasTag();
        $service2 = StaticService::staticHasTag();

        $this->assertEquals($service1, $service2);
        $this->assertNotSame($service1, $service2);

        StaticService::disableCreateNewInstance();
    }

    public function testGetBy()
    {
        $req = Wei::getBy(Req::class);
        $this->assertInstanceOf(Req::class, $req);
    }

    public function testInstance()
    {
        $req = Req::instance();
        $this->assertInstanceOf(Req::class, $req);

        $req2 = Req::instance();
        $this->assertSame($req, $req2);
    }

    public function testInstanceNewService()
    {
        $ret = Ret::instance();
        $this->assertInstanceOf(Ret::class, $ret);

        $ret2 = Ret::instance();
        $this->assertNotSame($ret, $ret2);
    }
}
