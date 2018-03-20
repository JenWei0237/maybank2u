<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Account;
use app\models\User;

class AccountForm extends Model
{
	public $account_number;
	public $name;
	public $type;
	public $balance;
	public $activation;

	public function rules()
    {
        return [
            [['name', 'account_number', 'type', 'balance'], 'required'],
            [['activation'], 'safe'],
        ];
    }

    public function activateAccount()
    {
    	$user = User::findOne($this->name);
    	$account = new Account;

    	$number = (string) rand(100000000, 999999999);

    	$account->user_id = $this->name;
    	$account->account_number = $number;
    	
    	$account->balance = 250;
    	$account->type = $this->type;
    	$account->created_at = date('Y-m-d H:i:s');
    	$account->updated_at = date('Y-m-d H:i:s');

    	$user->activation = 'Activated';

    	$user->update(false, ['activation']);
    	
    	if(!$account->save()){
    		throw new \Exception(current($account->getFirstErrors()));
    	}
    }

    public function getBalance()
    {
    	$account = new Account;
    	$this->balance = 250;

    }
}