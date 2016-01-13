<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Atest */
$tmpTitle = ucfirst(Yii::$app->controller->id);
$this->title = Yii::t('app', 'Create '.$tmpTitle);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $tmpTitle), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Yii::$app->controller->id ?>-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
