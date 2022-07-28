<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property int|null $id
 * @property string|null $name
 * @property int|null $user_count
 */
class TestGetSet extends BaseModel
{
    use ModelTrait;

    protected $casts = [
        'id' => 'int',
        'name' => 'string',
        'user_count' => 'int',
    ];
}
