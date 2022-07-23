<?php

namespace Wei;

use Wei\Db\QueryBuilderCacheTrait;
use Wei\Db\QueryBuilderPropsTrait;
use Wei\Db\QueryBuilderTrait;

/**
 * A SQL query builder class
 *
 * @author Twin Huang <twinhuang@qq.com>
 */
class QueryBuilder extends Base
{
    use QueryBuilderCacheTrait;
    use QueryBuilderPropsTrait;
    use QueryBuilderTrait;

    /**
     * @var bool
     */
    protected static $createNewInstance = true;
}
