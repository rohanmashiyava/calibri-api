<?php

use yii\db\Migration;

/**
 * Class m200630_205638_alter_relations
 */
class m200630_205638_alter_relations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `subscription_info` ADD FOREIGN KEY (`user_device_id`) REFERENCES `user_devices_info`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200630_205638_alter_relations cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200630_205638_alter_relations cannot be reverted.\n";

        return false;
    }
    */
}
