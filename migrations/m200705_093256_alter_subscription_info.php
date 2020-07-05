<?php

use yii\db\Migration;

/**
 * Class m200705_093256_alter_subscription_info
 */
class m200705_093256_alter_subscription_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `subscription_info` ADD `is_iap` TINYINT(1) NOT NULL AFTER `last_update_time`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200705_093256_alter_subscription_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200705_093256_alter_subscription_info cannot be reverted.\n";

        return false;
    }
    */
}
