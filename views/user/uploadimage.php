<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Upload';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-uploadimage">
    <h1><?= Html::encode($this->title) ?></h1>
        <?php $form = ActiveForm::begin([
          'id' => 'uploadimage-form',
          'layout' => 'horizontal',
          'options' => [
                'enctype' => 'multipart/form-data'
          ],
        ]); ?>
            <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*']) ?>
        <?php ActiveForm::end(); ?>
</div>
