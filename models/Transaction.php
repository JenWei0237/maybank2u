<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $user_id
 * @property string $from_account_number
 * @property string $to_account_number
 * @property string $details
 * @property double $amount
 * @property double $after_balance
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_deleted
 *
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['amount', 'after_balance'], 'double'],
            [['created_at', 'updated_at'], 'safe'],
            [['from_account_number', 'to_account_number', 'details', 'type'], 'string', 'max' => 255],
            [['is_deleted'], 'number', 'max' => 1],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'from_account_number' => 'From Account Number',
            'to_account_number' => 'To Account Number',
            'details' => 'Details',
            'amount' => 'Amount',
            'after_balance' => 'After Balance',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
