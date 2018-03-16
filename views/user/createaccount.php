<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Create Account';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-createaccount">
	<h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->session->hasFlash('Create account successfully')): ?>

   	<?php else: ?> 
    <?php $form = ActiveForm::begin([
      'id' => 'createaccount-form'
      // 'layout' => 'horizontal'
      ]); ?>

      <div class="row">
            <div class="col-lg-5">
                <?= $form->field($model, 'account_number')->textInput()->label('Account Number')?>
                <?= $form->field($model, 'type')->dropDownList([
                    'Checking Account' => 'Checking Account',
                    'Saving Account' => 'Saving Account'])->label('Account Type') ?>
                <br/>
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'create-button', 
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
   </div>
 <?php endif; ?>
</div>