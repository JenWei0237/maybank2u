<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-changepassword">
	<h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
      'id' => 'changepassword-form'
      ]); ?>

      <div class="row">
            <div class="col-lg-5">
                <?= $form->field($model, 'old_password')->passwordInput()->label('Old Password') ?>
                <?= $form->field($model, 'new_password')->passwordInput()->label('New Password') ?>
                <?= $form->field($model, 'confirm_new_password')->passwordInput()->label('Confirm New Password')?>
                <?= Html::submitButton('Change', ['class' => 'btn btn-primary', 'name' => 'change_button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
   </div>
</div>