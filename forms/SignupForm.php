<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class SignUpForm extends Model
{
	public $username;
    public $password;
    public $confirm_password;
	public $confirm_new_password;
    public $name;
	public $first_name;
	public $last_name;
    public $ic;
    public $dob;
    public $gender;
    public $email;
    public $country;
    public $country_code;
    public $contact_number;
    public $address;
    public $city;
    public $state;
    public $postcode;
    public $remark;
    public $security_code;
    public $old_password;
    public $new_password;


	public function rules()
    {
        return [
            [['username', 'confirm_password', 'confirm_new_password', 'password', 'old_password', 'new_password', 'name', 'first_name', 'last_name', 'dob', 'ic', 'email', 'country_code', 'contact_number', 'gender'], 'required'],
            ['email', 'email'],
            [['email'], 'unique'],
            [['dob'], 'default', 'value' => null],
            [['country', 'address', 'city', 'state', 'postcode', 'security_code'], 'safe'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
            ['confirm_new_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    public function signUp()
    {
        $model = new User;

            $model->username = $this->username;
            $model->password = sha1($this->password);
            $model->name = $this->name;
            $model->first_name = $this->first_name;
            $model->last_name = $this->last_name;
            $model->ic = $this->ic;
            $model->dob = $this->dob;
            $model->gender = $this->gender;
            $model->position = 'user';       
            $model->activation = 'Deactivate';       
            $model->email = $this->email;
            $model->country_code = $this->country_code;
            $model->contact_number = $this->contact_number;
            $model->address = $this->address;
            $model->postcode = $this->postcode;
            $model->city = $this->city;
            $model->state = $this->state;
            $model->country = $this->country;
            $model->is_suspended = 0;
            $model->remark = "";
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if(!$model->save()){
                throw new \Exception(current($model->getFirstErrors()));
            }
            return $model;
    }

    public function updateProfile($id)
    {
        $model = User::findOne($id);
        $model->name = $this->name;
        $model->first_name = $this->first_name;
        $model->last_name = $this->last_name;
        $model->ic = $this->ic;
        $model->dob = $this->dob;
        $model->email = $this->email;
        $model->country_code = $this->country_code;
        $model->contact_number = $this->contact_number;
        $model->address = $this->address;
        $model->postcode = $this->postcode;
        $model->city = $this->city;
        $model->state = $this->state;
        $model->country = $this->country;
        $model->username = $this->username;
        $model->password = $this->password;
        $model->gender = $this->gender;        
        $model->updated_at = date('Y-m-d H:i:s');

        if(!$model->save()){
            throw new \Exception(current()->getFirstErrors());
        }
    
        return $model;
    }

    public function getUserid()
    {
        throw new \Exception($this->username);
        $user = User::find()
                ->where(['username' => $this->username])
                ->one();

        if($user === null){
            throw new \Exception($this->username);
        }

        return $user;
    }

    public function requestCode($email)
    {
        $model = User::findOne(['email' => $this->email]);

        $code = rand(10000, 99999);

        $model->security_code = $code;
        $model->update(false, ['security_code']);

        return $code;
    }

    public function newPassword($security_code)
    {
        $user = User::findOne(['security_code' => $this->security_code]);
        if($user->security_code === $this->security_code){
            $user->password = sha1($this->password);
            $user->security_code = '';

            $user->update(false, ['password']);
            $user->update(false, ['security_code']);
        }else {
            throw new \Exception("The verification code does not match.");
        }
    }

    public function changePassword()
    {
        $user = User::findOne(Yii::$app->user->identity->id);

        if(sha1($this->old_password) === $user->password){
            $user->password = sha1($this->new_password);
            $user->update(false, ['password']);
        }else{
            throw new \Exception("Incorrect old password.");
        }

    }
}