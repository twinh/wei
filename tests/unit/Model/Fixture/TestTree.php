<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\Model\TreeTrait;
use Wei\ModelTrait;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $path
 * @property int $level
 */
class TestTree extends BaseModel
{
    use ModelTrait;
    use TreeTrait;
}
