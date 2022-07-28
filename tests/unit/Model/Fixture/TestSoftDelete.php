<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\Model\SoftDeleteTrait;
use Wei\ModelTrait;

/**
 * @property string|null $id
 * @property string|null $deleted_at
 */
class TestSoftDelete extends BaseModel
{
    use ModelTrait;
    use SoftDeleteTrait;
}
