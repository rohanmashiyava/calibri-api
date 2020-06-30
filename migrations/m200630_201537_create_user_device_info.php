<?php

use yii\db\Migration;

/**
 * Class m200630_201537_create_user_device_info
 */
class m200630_201537_create_user_device_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `user_devices_info` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `unique_device_id` VARCHAR(255) NOT NULL , `version_name` VARCHAR(255) NOT NULL , `version_code` VARCHAR(255) NOT NULL , `paid_app` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0-notpaid 1-paid' , `last_version` VARCHAR(255) NOT NULL , `created_date` INT(11) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200630_201537_create_user_device_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200630_201537_create_user_device_info cannot be reverted.\n";

        return false;
    }
    */
}
