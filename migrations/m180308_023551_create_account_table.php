<?php

use yii\db\Migration;

/**
 * Handles the creation of table `account`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180308_023551_create_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('account', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'account_number' => $this->string(),
            'balance' => $this->double(),
            'type' => $this->string(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'is_deleted' => $this->boolean()->defaultValue(0),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-account-user_id',
            'account',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-account-user_id',
            'account',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-account-user_id',
            'account'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-account-user_id',
            'account'
        );

        $this->dropTable('account');
    }
}
