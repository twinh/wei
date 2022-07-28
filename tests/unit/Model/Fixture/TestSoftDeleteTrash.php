<?php

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\Model\SoftDeleteTrait;
use Wei\ModelTrait;

/**
 * @property string|null $id
 * @property string|null $deleted_at
 * @property string|null $purged_at
 */
class TestSoftDeleteTrash extends BaseModel
{
    use ModelTrait;
    use SoftDeleteTrait;

    protected $enableTrash = true;
}
