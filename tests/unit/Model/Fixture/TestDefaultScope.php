<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

class TestDefaultScope extends BaseModel
{
    use ModelTrait {
        __construct as parentConstruct;
    }

    public function __construct(array $options = [])
    {
        $this->parentConstruct($options);

        $this->addDefaultScope('active');
        $this->addDefaultScope('typeA');
    }

    public function active()
    {
        return $this->where('active', true);
    }

    public function typeA()
    {
        return $this->where('type', 'A');
    }
}
