<?php

namespace Wei;

use Wei\Db\QueryBuilderPropsTrait;

/**
 * @internal 逐步完善后移到 Wei 中
 * @phpstan-ignore-next-line PHPStorm allows trait type to prompt code call
 * @mixin ModelTrait
 */
abstract class BaseModel extends Base implements \ArrayAccess, \IteratorAggregate, \Countable, \JsonSerializable
{
    use QueryBuilderPropsTrait;

    /**
     * @var bool
     */
    protected static $createNewInstance = true;

    protected $createdAtColumn = 'created_at';

    protected $createdByColumn = 'created_by';

    protected $updatedAtColumn = 'updated_at';

    protected $updatedByColumn = 'updated_by';

    /**
     * The primary key column
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Whether it's a new record and has not save to database
     *
     * @var bool
     */
    protected $new = true;

    /**
     * The data of model
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The fields that are assignable through fromArray method
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The fields that aren't assignable through fromArray method
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * The attribute values before changed
     *
     * @var array
     */
    protected $changes = [];

    /**
     * @var array
     */
    protected $virtual = [];

    /**
     * @var string[]
     */
    protected $hidden = [];

    protected static $booted = [];

    /**
     * Returns whether the model was inserted in the this request
     *
     * @var bool
     */
    protected $wasRecentlyCreated = false;

    /**
     * The attribute is set by the user, such as calling `$model->xxx = $value`.
     */
    protected const ATTRIBUTE_SOURCE_USER = 1;

    /**
     * The attribute is loaded from the database and is an undecoded/unconverted type string.
     */
    protected const ATTRIBUTE_SOURCE_DB = 2;

    /**
     * The attribute have been convert by PHP, such as type cast.
     */
    protected const ATTRIBUTE_SOURCE_PHP = 3;

    /**
     * The source of the current attribute values
     *
     * @var array
     */
    protected $attributeSources = [
        '*' => self::ATTRIBUTE_SOURCE_USER,
    ];
}
