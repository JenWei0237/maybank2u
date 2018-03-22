<?php 
namespace app\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Account;

class AccountSearchForm extends Account
{
	public function accountSearch($params)
	{
		$query = Account::find();

		$this->load($params);

		$query->joinWith('user');

		// $query->andFilterWhere([
		// 	'id' => $this->id,
		// 	'is_suspended' => $this->is_suspended,
		// ]);

		// $query->andFilterWhere(['like', 'id', $this->id])
		// 		->andFilterWhere(['like', 'name', $this->name])
		// 		->andFilterWhere(['like', 'ic', $this->ic]);

		return new ActiveDataProvider([
			'query' => $query,
		]);

	}
}
?>