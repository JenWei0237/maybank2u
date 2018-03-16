<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Account;

class CreateaccountForm extends Model
{
    public $account_number;
    public $type;

    public function rules()
    {
        return [
            [['account_number', 'type'], 'required'],
        ];
    }

    public function createAccount()
    {
        $id = Yii::$app->user->identity->id;
        $account = new Account;

        $account->user_id = $id;
        $account->account_number = $this->account_number;
        $account->balance = 5000;
        $account->type = $this->type;
        $account->created_at = date('Y-m-d H:i:s');
        $account->updated_at = date('Y-m-d H:i:s');

        if(!$account->save()){
            throw new \Exception(current($transaction->getFirstErrors()));
        }
    }
}