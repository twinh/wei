<?php

namespace WeiTest\Fixtures\App\Controller;

use WeiTest\Fixtures\app\middleware\After;
use WeiTest\Fixtures\app\middleware\All;
use WeiTest\Fixtures\app\middleware\Around;
use WeiTest\Fixtures\app\middleware\Before;
use WeiTest\Fixtures\app\middleware\Except;
use WeiTest\Fixtures\app\middleware\Only;
use WeiTest\Fixtures\app\middleware\ReturnArray;
use WeiTest\Fixtures\app\middleware\ReturnString;
use WeiTest\Fixtures\app\middleware\Stack;
use WeiTest\Fixtures\app\middleware\Stack2;
use WeiTest\Fixtures\app\middleware\Stack3;

/**
 * @property \Wei\Response $response
 */
class Middleware extends \Wei\BaseController
{
    protected $middleware = array();

    public function __construct($options)
    {
        parent::__construct($options);

        $this->middleware(All::className());

        $this->middleware(Only::className(), array('only' => 'only'));

        $this->middleware(Except::className(), array('except' => array(
            'only', 'before', 'after', 'around'
        )));

        $this->middleware(Before::className(), array('only' => 'before'));

        $this->middleware(After::className(), array('only' => 'after'));

        $this->middleware(Around::className(), array('only' => 'around'));

        $this->middleware(Stack::className(), array('only' => 'stack'));
        $this->middleware(Stack2::className(), array('only' => 'stack'));
        $this->middleware(Stack3::className(), array('only' => 'stack'));

        $this->middleware(ReturnString::className(), array('only' => 'returnString'));

        $this->middleware(ReturnArray::className(), array('only' => 'returnArray'));
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
