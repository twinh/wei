<?php

declare(strict_types=1);

namespace WeiTest\Model\Fixture;

use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * @property TestArticle|TestArticle[] $articles
 * @property string $name
 */
class TestTag extends BaseModel
{
    use ModelTrait;

    public function articles()
    {
        return $this->belongsToMany(TestArticle::class);
    }
}
