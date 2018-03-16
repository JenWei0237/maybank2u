<?php 
namespace app\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Account;

class ViewForm extends Account
{
	public $id;

	public function search(this->id)
	{
		$query = Account::findOne($id);

		// $this->load($params);

		return new ActiveDataProvider([
			'query' => $query,
		]);

	}
}
?>