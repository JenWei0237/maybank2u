<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Account;

$this->title = 'Account List';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-viewaccountlist">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'attribute' => 'user_id',
                    'value' => 'user.username'
                ],
                'account_number',
                'balance',
                'type',
                'created_at',
                'updated_at',
                'is_deleted'
            ],
        ]);
    ?>
</div>
