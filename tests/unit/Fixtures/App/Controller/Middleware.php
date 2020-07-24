<?php

namespace WeiTest\Fixtures\App\Controller;

use WeiTest\Fixtures\App\Middleware\After;
use WeiTest\Fixtures\App\Middleware\All;
use WeiTest\Fixtures\App\Middleware\Around;
use WeiTest\Fixtures\App\Middleware\Before;
use WeiTest\Fixtures\App\Middleware\Except;
use WeiTest\Fixtures\App\Middleware\Only;
use WeiTest\Fixtures\App\Middleware\ReturnArray;
use WeiTest\Fixtures\App\Middleware\ReturnString;
use WeiTest\Fixtures\App\Middleware\Stack;
use WeiTest\Fixtures\App\Middleware\Stack2;
use WeiTest\Fixtures\App\Middleware\Stack3;

/**
 * @property \Wei\Response $response
 */
class Middleware extends \Wei\BaseController
{
    protected $middleware = [];

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware(All::className());

        $this->middleware(Only::className(), ['only' => 'only']);

        $this->middleware(Except::className(), ['except' => [
            'only', 'before', 'after', 'around',
        ]]);

        $this->middleware(Before::className(), ['only' => 'before']);

        $this->middleware(After::className(), ['only' => 'after']);

        $this->middleware(Around::className(), ['only' => 'around']);

        $this->middleware(Stack::className(), ['only' => 'stack']);
        $this->middleware(Stack2::className(), ['only' => 'stack']);
        $this->middleware(Stack3::className(), ['only' => 'stack']);

        $this->middleware(ReturnString::className(), ['only' => 'returnString']);

        $this->middleware(ReturnArray::className(), ['only' => 'returnArray']);
    }

    public function onlyAction()
    {
        return 'only';
    }

    public function exceptAction()
    {
        return 'except';
    }

    public function beforeAction()
    {
        return 'before';
    }

    public function afterAction()
    {
        return 'after';
    }

    public function aroundAction()
    {
        return 'around';
    }

    public function stackAction()
    {
        return $this->response->setContent($this->response->getContent() . 'stack');
    }

    public function returnStringAction()
    {
    }

    public function returnArrayAction()
    {
    }
}
