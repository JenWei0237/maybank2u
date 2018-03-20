<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Forget Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-forgetpassword">
	<h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
      'id' => 'forgetpassword-form',
      'layout' => 'horizontal'
      ]); ?>

      <div class="row">
            <div class="col-lg-5">
                <p>Please fill in the following blank.</p>
                <?= $form->field($model, 'email')->textInput()?>
                <?= Html::submitButton('Next', ['class' => 'btn btn-primary', 'name' =>'next-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
   </div>
</div>