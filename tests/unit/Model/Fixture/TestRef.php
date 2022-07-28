<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property int $id
 * @property array $json
 * @property mixed $mixed
 */
class TestRef extends BaseModel
{
    use ModelTrait;

    protected $columns = [
        'id' => [
            'cast' => 'int',
        ],
        'json' => [
            'cast' => 'json',
            'default' => [],
        ],
        'mixed' => [
            'cast' => null,
        ],
    ];
}
