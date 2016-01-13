<?php

/* @var $this \yii\web\View */
/* @var $content string */

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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=device-dpi">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title . ' - ' . Yii::$app->params['projectName'] . ' - ' . Yii::$app->params['companyName']) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['projectName'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= date('Y') .' '. Yii::$app->params['companyName'] ?>. All rights reserved.</p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<div id="alert" class="center alert message fade in hide">
    <a class="dismiss close" data-dismiss="alert">×</a>
    <label id="msg"></label>
</div>
<div class="loading">
  <div class="loading-bg"></div>
  <div class="loading-icon">
    <div class="f_circleG" id="frotateG_01">
    </div>
    <div class="f_circleG" id="frotateG_02">
    </div>
    <div class="f_circleG" id="frotateG_03">
    </div>
    <div class="f_circleG" id="frotateG_04">
    </div>
    <div class="f_circleG" id="frotateG_05">
    </div>
    <div class="f_circleG" id="frotateG_06">
    </div>
    <div class="f_circleG" id="frotateG_07">
    </div>
    <div class="f_circleG" id="frotateG_08">
    </div>
  </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
