<?php

use yii\db\Migration;

/**
 * Class m180320_094815_insert_account_table
 */
class m180320_094815_insert_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('account', array(
            'user_id' => 1,
            'account_number' => '159357789',
            'balance' => 10000000,
            'type' => 'Saving Account'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180320_094815_insert_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180320_094815_insert_account_table cannot be reverted.\n";

        return false;
    }
    */
}
