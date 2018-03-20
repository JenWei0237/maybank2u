<?php

use yii\db\Migration;

/**
 * Class m180320_032942_insert_user_table
 */
class m180320_032942_insert_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', array(
            'username' => 'admin1',
            'password' => sha1('admin1'),
            'name' => 'admin1',
            'first_name' => 'admin1',
            'last_name' => 'admin1',
            'ic' => '789659125878',
            'dob' => '1987-01-01',
            'gender' => 'Male',
            'position' => 'admin',
            'email' => 'admin@example.com',
            'country_code' => '+60',
            'contact_number' => '123456789',
            'address' => 'ads',
            'postcode' => '95200',
            'city' => 'asd',
            'state' => 'Johor',
            'country' => 'Malaysia',
            'remark' => null,
            'security_code' => null 
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180320_032942_insert_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180320_032942_insert_user_table cannot be reverted.\n";

        return false;
    }
    */
}
