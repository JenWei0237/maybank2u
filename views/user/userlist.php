<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\User;

$this->title = 'User List';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-userlist">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                'id',
                'username',
                'position',               
                'email',
                'name',
                'activation',
                'created_at',
                'updated_at',
                'is_suspended',
                'is_deleted'
            ],
        ]);
    ?>
</div>
