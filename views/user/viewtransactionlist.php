<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Transaction;

$this->title = 'Account List';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-viewtransactionlist">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'attribute' => 'user_id',
                    'value' => 'user.username'
                ],
                'from_account_number',
                'to_account_number',
                'details',
                'amount',
                'after_balance',
                'type',
                'created_at',
                'updated_at',
                'is_deleted'
            ],
        ]);
    ?>
</div>
