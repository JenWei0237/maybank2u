<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180313_050807_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'from_account_number' => $this->string(),
            'to_account_number' => $this->string(),
            'details' => $this->string(),
            'amount' => $this->double(),
            'after_balance' => $this->double(),
            'type' => $this->string(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'is_deleted' => $this->boolean()->defaultValue(0),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-transaction-user_id',
            'transaction',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-transaction-user_id',
            'transaction',
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
            'fk-transaction-user_id',
            'transaction'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-transaction-user_id',
            'transaction'
        );

        $this->dropTable('transaction');
    }
}
