<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\Model\SoftDeleteTrait;
use Wei\ModelTrait;

/**
 * @property int|null $id
 * @property int|null $status
 */
class TestSoftDeleteStatus extends BaseModel
{
    use ModelTrait;
    use SoftDeleteTrait;

    public const STATUS_NORMAL = 1;

    public const STATUS_DELETED = 9;

    protected $table = 'test_soft_deletes';

    protected $deleteStatusColumn = 'status';

    protected $attributes = [
        'status' => self::STATUS_NORMAL,
    ];

    protected function getDeleteStatusValue()
    {
        return static::STATUS_DELETED;
    }

    protected function getRestoreStatusValue()
    {
        return static::STATUS_NORMAL;
    }
}
