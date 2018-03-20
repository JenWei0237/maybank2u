<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            //Public 
            ['label' => 'Home', 'url' => ['/user/index']],
            ['label' => 'Transaction', 'visible' => !Yii::$app->user->isGuest,'items' => [
                ['label' => 'Account Transaction', 'url' => ['/user/transfer']],
                ['label' => 'Transaction History', 'url' => ['/user/transactionhistory']],
            ]],
            // ['label' => 'About', 'url' => ['/user/about']],
            // ['label' => 'Contact', 'url' => ['/user/contact']],
            ['label' => 'Sign Up', 'url' => ['/user/signup']],
            ['label' => 'Profile', 'visible' => !Yii::$app->user->isGuest, 'items' => [
                ['label' => 'User Profile', 'url' => ['/user/userprofile']],
            ]],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/user/login']]
            ) : (
                ['label' => 'Account (' . Yii::$app->user->identity->username . ') ', 'visible' => !Yii::$app->user->isGuest, 'items' => [
                    ['label' => 'View Account',  'url' => ['/user/viewaccount']],
                    ['label' => 'Activate Account', 'url' => ['/user/activateaccount'], 'visible' => Yii::$app->user->identity->position === 'admin'],
                    '<li class = "divider"></li>',
                    ['label' => 'Logout' , 'url' => ['/user/logout'], 'template' => '<a href="{url}" data-method=post>{label}</a>'],
                ]]
            )]]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
