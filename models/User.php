<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $first_name
 * @property string $last_name
 * @property string $ic
 * @property string $dob
 * @property string $gender
 * @property string $position
 * @property string $email
 * @property string $country_code
 * @property string $contact_number
 * @property string $address
 * @property string $postcode
 * @property string $city
 * @property string $state
 * @property string $country
 * @property int $is_suspended
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_deleted
 *
 * @property Account[] $accounts
 * @property Transaction[] $transactions
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['username', 'password', 'name', 'first_name', 'last_name', 'ic', 'gender', 'position', 'email', 'country_code', 'contact_number', 'address', 'postcode', 'city', 'state', 'country', 'remark', 'security_code'], 'string', 'max' => 255],
            [['username'], 'required'],
            [['email'], 'unique'],
            [['is_suspended', 'is_deleted'], 'number', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'ic' => 'Ic',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'position' => 'Position',
            'email' => 'Email',
            'country_code' => 'Country Code',
            'contact_number' => 'Contact Number',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'is_suspended' => 'Is Suspended',
            'remark' => 'Remark',
            'security_code' => 'Security Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }
}
