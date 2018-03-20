<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-nextforgetpassword">
	<h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->session->hasFlash('Verification code has been sent to your email, please proceed to your email.')): ?>

    <?php else: ?>
    <?php $form = ActiveForm::begin([
      'id' => 'nextforgetpassword-form',
      'layout' => 'horizontal'
      ]); ?>

      <div class="row">
            <div class="col-lg-7">

                <?= $form->field($model, 'password')->passwordInput()->label('New Password') ?>
                <?= $form->field($model, 'confirm_password')->passwordInput()?>
                <?= $form->field($model, 'security_code')->textInput()->label('Verification Code')?>

                <div class="row">
                    <?= Html::submitButton('Confirm', ['class' => 'btn btn-primary', 'name' => 'confirm_button']) ?>
                    <!-- <?= Html::submitButton('Request verification code', ['class' => 'btn btn-primary', 'name' => 'submit', 'value' => 'Request']) ?> -->
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    <?php endif; ?>
   </div>
</div>