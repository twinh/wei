<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property TestUser|TestUser[] $users
 */
class TestUserGroup extends BaseModel
{
    use ModelTrait;

    public function users()
    {
        return $this->hasMany(TestUser::class, 'group_id');
    }
}
