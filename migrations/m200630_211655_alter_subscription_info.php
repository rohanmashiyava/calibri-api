<?php

use yii\db\Migration;

/**
 * Class m200630_211655_alter_subscription_info
 */
class m200630_211655_alter_subscription_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `subscription_info` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200630_211655_alter_subscription_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200630_211655_alter_subscription_info cannot be reverted.\n";

        return false;
    }
    */
}
