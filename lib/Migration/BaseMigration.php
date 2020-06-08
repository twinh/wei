<?php

namespace Wei\Migration;

use Wei\Base;

/**
 * @mixin \SchemaMixin
 * @mixin \DbMixin
 */
abstract class BaseMigration extends Base
{
    /**
     * Run the migration.
     */
    public function up()
    {
    }

    /**
     * Revert the migration.
     */
    public function down()
    {
    }
}
