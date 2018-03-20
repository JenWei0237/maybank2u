<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Account;
use app\models\Transaction;

class TransferForm extends Model
{
    const TYPE_IN = 'In';
    const TYPE_OUT = 'Out';

	public $from_account_number;
    public $to_account_number;
    public $account_number;
    public $amount;
	public $balance;
    public $name;
    public $user_id;
    public $details;
    public $after_balance;
    public $type;
    public $bank_reference;

	public function rules()
    {
        return [
            [['from_account_number', 'to_account_number', 'amount', 'name', 'details', 'bank_reference'], 'required'],
            [['amount', 'after_balance', 'balance'], 'number'],
            ['to_account_number', 'compare', 'compareAttribute' => 'from_account_number', 'operator' => '!='],
            [['amount'], 'number', 'min' => 0]
        ];
    }

    public function transferAccount()
    {
        $id = Yii::$app->user->identity->id;
        $out_transaction = new Transaction;
        $in_transaction = new Transaction;
        // $account = Account::findOne($id);
        $account2 = Account::findOne(['account_number' => $this->from_account_number]);

        if($account2->balance < $this->amount){
            throw new \Exception("Account balance is insufficient");
        }

        $out_transaction->user_id = $id;
        $out_transaction->from_account_number = $this->from_account_number;
        $out_transaction->to_account_number = $this->to_account_number;
        $out_transaction->details = $this->details;
        $out_transaction->amount = $this->amount;
        $out_transaction->after_balance = $this->getBalance($out_transaction->from_account_number) - $this->amount;
        $out_transaction->type = self::TYPE_OUT;
        $out_transaction->created_at = date('Y-m-d H:i:s');
        $out_transaction->updated_at = date('Y-m-d H:i:s');

        $this->getAccountBalance($out_transaction->from_account_number, $out_transaction->after_balance);

        if(!$out_transaction->save()){
            throw new \Exception(current($out_transaction->getFirstErrors()));
        }

        $in_transaction->user_id = $this->getToUserid($out_transaction->to_account_number);
        $in_transaction->from_account_number = $this->from_account_number;
        $in_transaction->to_account_number = $this->to_account_number;
        $in_transaction->details = $this->details;
        $in_transaction->amount = $this->amount;
        $in_transaction->after_balance = $this->getToBalance($out_transaction->to_account_number) + $this->amount;
        $in_transaction->type = self::TYPE_IN;
        $in_transaction->created_at = date('Y-m-d H:i:s');
        $in_transaction->updated_at = date('Y-m-d H:i:s');

        if(!$in_transaction->save()){
                throw new \Exception(current($out_transaction->getFirstErrors()));
        }

        $this->getAccountBalance($out_transaction->to_account_number, $in_transaction->after_balance);
    }

    public function getAccountnumber()
    {
        $id = Yii::$app->user->identity->id;
        $account = Account::findOne($id);

        $this->from_account_number = $account->account_number;
    }

    public function getToUserid($to_account_number)
    {
        $account = Account::find()
                ->select('user_id')
                ->where(['account_number' => $to_account_number])
                ->one();
        return $account->user_id;
    }

    public function getBalance($from_account_number)
    {
        $account = Account::find()
                ->select('balance')
                ->where(['account_number' => $from_account_number])
                ->one();
        return $account->balance;
    }

    public function getToBalance($to_account_number)
    {
        $account = Account::find()
                ->select('balance')
                ->where(['account_number' => $to_account_number])
                ->one();
        return $account->balance;
    }

    public function getAccountBalance($account_number, $after_balance)
    {
        $account = Account::find()
                ->where(['account_number' => $account_number])
                ->one();

        $account->balance = $after_balance;

        if(!$account->update(false, ['balance'])){
            throw new Exception(current($account->getFirstErrors()));
        }
    }
}