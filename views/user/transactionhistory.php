<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Transaction;

$this->title = 'Transaction History';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = new ActiveDataProvider([
    'query' => Transaction::find()->where(['user_id' => Yii::$app->user->identity->id]),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>

<div class="user-transactionhistory">
    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'from_account_number',
        'to_account_number',
        'details',
        'amount',
        'after_balance',
        'type',
        'created_at',
        // ['class' => 'yii\grid\ActionColumn'],

        ],
    ]);
    ?>
</div>
