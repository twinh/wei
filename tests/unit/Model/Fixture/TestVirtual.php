<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property mixed $virtual_column
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 */
class TestVirtual extends BaseModel
{
    use ModelTrait;

    protected $table = 'test_virtual';

    protected $virtual = [
        'virtual_column',
        'full_name',
        'only_get',
    ];

    protected $virtualColumnValue;

    public function getVirtualColumnAttribute()
    {
        return $this->virtualColumnValue;
    }

    public function setVirtualColumnAttribute($value)
    {
        $this->virtualColumnValue = $value;
    }

    public function getVirtualColumnValue()
    {
        return $this->virtualColumnValue;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setFullNameAttribute($fullName)
    {
        [$this->first_name, $this->last_name] = explode(' ', $fullName);
    }

    public function getOnlyGetAttribute()
    {
        return 'test';
    }
}
