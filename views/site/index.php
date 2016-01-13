<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'title';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p>
        <?php
        echo Html::a('Get started with Gii', ['/gii'], ['class' => 'btn btn-lg btn-success']);
        ?>
        </p>
    </div>

    <div class="body-content">

    </div>
</div>
