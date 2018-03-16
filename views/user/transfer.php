<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Account;

$this->title = 'Transaction';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-transfer">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->session->hasFlash('Amount transfered successfully.')): ?>

    <?php else: ?>
        <?php $form = ActiveForm::begin([
          'id' => 'transfer-form',
          'layout' => 'horizontal',
          'options' => [
                'enctype' => 'multipart/form-data'
          ],
        ]); ?>
        <div class="row">
            <div class="col-lg-7">
                <h4><b>From: Recipient</b></h4>
                <?= $form->field($model, 'from_account_number')->dropDownList($listData)->label('Account Number')?>
                <?= $form->field($model, 'details')->dropDownList([
                    'Fund Transfer' => 'Fund Transfer',
                    'Loan Payment' => 'Loan Payment',
                    'Others' => 'Others'])->label('Transaction Type') ?>
                <?= $form->field($model, 'amount')->textInput(['autofocus' => true])?>
                <h4><b>To: Recipient</b></h4>
                <?= $form->field($model, 'to_account_number')->label('Account Number')?>
                <?= $form->field($model, 'bank_reference')->textInput()?>
                <br/>
                <?= Html::submitButton('Confirm', ['class' => 'btn btn-primary', 'name' => 'transfer-button', 
                    'data' => [
                        'confirm' => 'Are you sure you want to transfer to this account?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
