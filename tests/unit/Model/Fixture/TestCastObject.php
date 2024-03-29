<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property int $id
 */
class TestCastObject extends BaseModel
{
    use ModelTrait;

    protected $columns = [
        'object_column' => [
            'cast' => 'object',
        ],
    ];
}
