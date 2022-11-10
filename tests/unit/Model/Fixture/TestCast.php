<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property int $int_column
 * @property int|null $nullable_int_column
 * @property int|null $nullable_default_int_column
 * @property string $big_int_column
 * @property string|null $nullable_big_int_column
 * @property bool $bool_column
 * @property string $string_column
 * @property string|null $datetime_column
 * @property string|null $date_column
 * @property array $json_column
 * @property object $object_column
 * @property object|null $nullable_object_column
 * @property object $default_object_column
 * @property array $list_column
 * @property array $list2_column
 * @property string $decimal_column
 * @property string|null $nullable_decimal_column
 */
class TestCast extends BaseModel
{
    use ModelTrait;

    protected $primaryKey = 'int_column';

    protected $columns = [
        'int_column' => [
            'cast' => 'int',
        ],
        'bool_column' => [
            'cast' => 'bool',
        ],
        'string_column' => [
            'cast' => 'string',
        ],
        'datetime_column' => [
            'cast' => 'datetime',
        ],
        'date_column' => [
            'cast' => 'date',
        ],
        'json_column' => [
            'cast' => 'array',
            'default' => [],
        ],
        'object_column' => [
            'cast' => 'object',
        ],
        'nullable_object_column' => [
            'cast' => 'object',
        ],
        'default_object_column' => [
            'cast' => 'object',
        ],
        'list_column' => [
            'cast' => 'list',
            'default' => [],
        ],
        'list2_column' => [
            'cast' => [
                'list',
                'type' => 'int',
                'separator' => '|',
            ],
            'default' => [],
        ],
    ];

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
