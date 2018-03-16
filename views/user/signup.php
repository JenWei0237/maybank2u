<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-signup">
	<h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->session->hasFlash('Register successfully')): ?>

   	<?php else: ?> 
    <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
      <div class="col-md-12">
        <?= $form->field($model, 'username')->textInput(['class' => 'form-control']) ?>
      </div>
      <div class="col-md-12">
        <?= $form->field($model, 'password')->textInput(['type' => 'password', 'class' => 'form-control']) ?>
      </div>
      <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput(['class' => 'form-control']) ?>
      </div>
      <div class="col-md-6">
        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-control']) ?>
      </div>
      <div class="col-md-6">
        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-control']) ?>
      </div>
      <div class="col-md-6">
        <?= $form->field($model, 'ic')->textInput(['placeholder' => '111111223333','class' => 'form-control'])->label('IC Number') ?>
      </div>
      <div class="col-md-6">
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'aaa@example.com', 'class' => 'form-control']) ?>
      </div>
      <div class="row">
          <div class="col-md-12">
            <?= $form->field($model, 'dob')->
                widget(DatePicker::className(), [
                  'language' => 'en',
                  'dateFormat' => 'yyyy-MM-dd',
                  'clientOptions' => 
                  [
                      'showAnim' => 'drop',
                      'yearRange' => '1900:2099',
                      'changeMonth' => true,
                      'changeYear' => true
                  ]
                  
                ])->label('Date of Birth')
            ?>
          </div>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'country_code')->dropDownList(['+60' => '+60']) ?>
      </div>
      <div class="col-md-10">
        <?= $form->field($model, 'contact_number')->textInput(['placeholder' => '0123456789','class' => 'form-control']) ?>
      </div>
      <div class="col-md-12">
        <?= $form->field($model, 'gender')->radioList(array('Male'=>'Male', 'Female'=>'Female')) ?>
      </div>
      <div class="col-md-12">
        <?= $form->field($model, 'address')->textInput(['class' => 'form-control']) ?>
      </div>
      <div class="col-md-3">
        <?= $form->field($model, 'country')->dropDownList(['Malaysia' => 'Malaysia']) ?>
      </div>
      <div class="col-md-4">
        <?= $form->field($model, 'state')->dropDownList([
          'Johor' => 'Johor',
          'Kedah' => 'Kedah',
          'Kelantan' => 'Kelantan',
          'Melaka' => 'Melaka',
          'Negeri Sembilan' => 'Negeri Sembilan',
          'Pahang' => 'Pahang',
          'Pulay Pinang' => 'Pulau Pinang',
          'Perak' => 'Perak',
          'Perlis' => 'Perlis',
          'Sabah' => 'Sabah',
          'Sarawak' => 'Sarawak',
          'Selangor' => 'Selangor',
          'Terengganu' => 'Terengganu',
          'Kuala Lumpur' => 'Kuala Lumpur',
          'Labuan' => 'Labuan',
          'Putrajaya' => 'Putrajaya']) ?>
      </div>
      <div class="col-md-3">
        <?= $form->field($model, 'city')->textInput(['class' => 'form-control']) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'postcode')->textInput(['placeholder' => '71000','class' => 'form-control']) ?>
      </div>
      <div class="col-md-1">
        <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
      </div>
    <?php ActiveForm::end(); ?>
   </div>
 <?php endif; ?>
</div>