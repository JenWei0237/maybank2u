<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Account;

$this->title = 'Account Details';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = new ActiveDataProvider([
    'query' => Account::find()->where(['user_id' => Yii::$app->user->identity->id]),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>

<div class="site-viewaccount">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                'account_number',
                'balance',
                'type',
            ],
        ]);
    ?>
</div>
