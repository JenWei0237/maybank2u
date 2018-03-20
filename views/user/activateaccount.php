<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Activate Account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-activateaccount">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->session->hasFlash('Amount transfered successfully.')): ?>

    <?php else: ?>
        <?php $form = ActiveForm::begin([
          'id' => 'activate-form',
          'layout' => 'horizontal']); 
        ?>
        <div class="row">
            <div class="col-lg-7">
                <?= $form->field($model, 'name')->dropDownList($listData)?>
                <?= $form->field($model, 'type')->dropDownList([
                    'Checking Account' => 'Checking Account',
                    'Saving Account' => 'Saving Account'])->label('Account Type') ?>
                <?= $form->field($model, 'balance')->textInput(['readonly' => true])?>
                <br/>
                <?= Html::submitButton('Activate', ['class' => 'btn btn-primary', 'name' => 'activate-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
