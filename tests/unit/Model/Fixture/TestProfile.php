<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property int|null $id
 * @property string|null $test_user_id
 * @property string|null $description
 */
class TestProfile extends BaseModel
{
    use ModelTrait;
}
