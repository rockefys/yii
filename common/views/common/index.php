<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$tmpTitle = ucfirst(Yii::$app->controller->id);
$this->title = Yii::t('app', $tmpTitle);
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('query', [
        'model' => $model,
    ]) ?>
<div class="<?= Yii::$app->controller->id ?>-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create '.$tmpTitle), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge(
            [['class' => 'yii\grid\SerialColumn']],
            $columns,
            [['class' => 'yii\grid\ActionColumn']]
        )
    ]); ?>

</div>
