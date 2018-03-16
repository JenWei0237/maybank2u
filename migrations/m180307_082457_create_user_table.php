<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180307_082457_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'password' => $this->string(),
            'name' => $this->string(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'ic' => $this->string(),
            'dob' => $this->date(),
            'gender' => $this->string(),
            'position' => $this->string(),
            'email' => $this->string(),
            'country_code' => $this->string(),
            'contact_number' => $this->string(),
            'address' => $this->string(),
            'postcode' => $this->string(),
            'city' => $this->string(),
            'state' => $this->string(),
            'country' => $this->string(),
            'is_suspended' => $this->boolean()->defaultValue(0),
            'remark' => $this->string(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'is_deleted' => $this->boolean()->defaultValue(0),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
