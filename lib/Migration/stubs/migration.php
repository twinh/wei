<?php

echo '<?php';
?>


namespace <?= $namespace ?>;

use Miaoxing\Services\Migration\BaseMigration;

class <?= $class ?> extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        //
    }
}
