<?php

namespace WeiTest\Fixtures;

use Wei\Base;

class StaticService extends Base
{
    /**
     * @svc
     */
    protected function staticHasTag()
    {
        return $this;
    }

    protected function staticDontHaveTag()
    {
        return $this;
    }

    /**
     * @svc
     */
    protected function dynamicHasTag()
    {
        return __FUNCTION__;
    }

    protected function dynamicDontHaveTag()
    {
        return __FUNCTION__;
    }
}
