<?php

use yii\db\Migration;

/**
 * Class m200630_202219_create_subscription_info
 */
class m200630_202219_create_subscription_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `subscription_info` ( `id` INT(11) NOT NULL , `user_device_id` INT(11) NOT NULL , `auto_renewing` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-no auto renew 1- auto renew' , `expiry_time` INT(11) NOT NULL , `is_expired` TINYINT(1) NULL DEFAULT NULL COMMENT '0-not expired 1-expired' , `last_update_time` INT(11) NOT NULL , `order_id` VARCHAR(255) NOT NULL , `product_id` VARCHAR(255) NOT NULL , `purchase_state` VARCHAR(255) NOT NULL , `purchase_time` INT(11) NOT NULL ) ENGINE = InnoDB;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200630_202219_create_subscription_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200630_202219_create_subscription_info cannot be reverted.\n";

        return false;
    }
    */
}
