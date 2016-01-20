<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\widgets\LinkPager;
use common\widgets\ActiveFormQuery;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$tmpTitle = ucfirst(Yii::$app->controller->id);
$this->title = Yii::t('app', $tmpTitle);
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode('') ?></h1>

    <?= ActiveFormQuery::widget([
        'model' => $model
    ]); ?>
<div class="<?= Yii::$app->controller->id ?>-index">


    <p>
        <?= Html::a(Yii::t('app', 'Create '.$tmpTitle), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge(
            [['class' => 'yii\grid\SerialColumn']],
            $columns,
            [['class' => 'yii\grid\ActionColumn']]
        ),
        'layout' => "{items}\n{pager}\n",
        'pager' => [
            'class' => LinkPager::className(),
                'firstPageLabel' => true,
                'lastPageLabel' => true
        ]
    ]); ?>

</div>
