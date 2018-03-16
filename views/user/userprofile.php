<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;

$this->title = 'User Profile';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-userprofile">	
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => 
		[
			'name',
			'first_name',
			'last_name',
			'ic',
			'dob',
			'gender',
			'email',
			'country_code',
			'contact_number',
			'address',
			'postcode',
			'city',
			'state',
			'country'
		],
	]);
	?>
	<?= Html::a('Update', ['updateprofile', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
</div>
