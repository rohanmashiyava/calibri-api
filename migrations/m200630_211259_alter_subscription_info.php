<?php

use yii\db\Migration;

/**
 * Class m200630_211259_alter_subscription_info
 */
class m200630_211259_alter_subscription_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `subscription_info` ADD PRIMARY KEY(`id`);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200630_211259_alter_subscription_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200630_211259_alter_subscription_info cannot be reverted.\n";

        return false;
    }
    */
}
