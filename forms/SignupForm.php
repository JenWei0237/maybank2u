<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class SignUpForm extends Model
{
	public $username;
	public $password;
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

	public function rules()
    {
        return [
            [['username', 'password', 'name', 'first_name', 'last_name', 'dob', 'ic', 'email', 'country_code', 'contact_number', 'gender'], 'safe'],
            ['email', 'email'],
            [['country', 'address', 'city', 'state', 'postcode'], 'safe'],
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
}