<?php

use yii\db\Migration;

/**
 * Class m200704_165223_alter_subscription_info
 */
class m200704_165223_alter_subscription_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `subscription_info` CHANGE `order_id` `order_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `product_id` `product_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `purchase_state` `purchase_state` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `purchase_time` `purchase_time` INT(11) NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200704_165223_alter_subscription_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200704_165223_alter_subscription_info cannot be reverted.\n";

        return false;
    }
    */
}
