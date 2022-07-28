<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\Model\CamelCaseTrait;
use Wei\ModelTrait;

class TestCamelCase extends BaseModel
{
    use CamelCaseTrait;
    use ModelTrait;
}
