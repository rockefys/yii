<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Atest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Yii::$app->controller->id ?>-query">

    <?php $form = ActiveForm::begin(['options'=>['class'=>'form-inline form-query'],'enableClientValidation' => false]); ?>
    <?php
    //text|textarea|select|checkbox|date|datetime|time|refer|list|func
    $controlTypes = [
        'label'     => 'label',
        'text'      => 'textInput',
        'password'  => 'passwordInput',
        'textarea'  => 'textarea',
        'select'    => 'dropDownList',
        'list'      => 'listBox',
        'checkbox'  => 'checkbox',
        'checkboxList' => 'checkboxList',
        'radio'     => 'radio',
        'radioList' => 'radioList',
        'link'      => 'link',
        'file'      => 'fileInput',
        'image'     => 'image',
        'widget'    => 'widget',
        'date'      => 'textInput',
        'datetime'  => 'textInput',
        'decimal'   => 'textInput',
        'time'      => 'textInput',
        'refer'     => 'textInput',
        'func'      => 'textInput'
    ];
    $i = 0;
    $cols = 4;
    $fluid = 12;
    $lgCol = $fluid / $cols;
    $mdCol = $lgCol * 2;
    $smCol = $lgCol * 2;
    $xsCol = $fluid;
    $colClass = 'col-lg-'. $lgCol . ' ' .'col-md-'. $mdCol . ' ' . 'col-sm-'. $smCol . ' ' . 'col-xs-'. $xsCol;
    $controls = $model->controlTypes();
    $labels = $model->attributeLabels();
    foreach ($model->queryFields() as $key => $val) {
        if($i % $cols == 0) {
            echo Html::beginTag('p');
            echo Html::beginTag('div', ['class' => 'row']);
        }
        echo Html::beginTag('div', ['class' => 'form-group query-'.$model::className().'-'.$key.' '.$colClass]);
        echo Html::beginTag('div', ['class' => 'input-group col-lg-12 col-md-12 col-sm-12 col-xs-12']);
        echo Html::Tag('span', $labels[$key] ,['class' => 'input-group-addon']);
        $options = [];
        if(is_string($controls[$key])) {
            if(in_array($key, ['textbox','password','textarea'])) {
                $options['maxlength'] = true;
                $options['rows'] = 6;
            }
            echo Html::$controlTypes[$controls[$key]]('query['.$key.']','',['class' => 'form-control']);
        } elseif(is_array($controls[$key])) {
            echo Html::$controlTypes[current($controls[$key])]($key,'',next($controls[$key]), array_merge(['class' => 'form-control'],next($controls[$key])));
        }
        echo Html::endTag('div');
        echo Html::endTag('div');
        ++$i;
        if(($i % $cols == 0) && $i != 0 || $i == count($model->queryFields())) {
            echo Html::endTag('div');
            echo Html::endTag('p');
        }
    }
    ?>

    <div class="row">
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Html::submitButton(Yii::t('app', 'Query'), ['class' => 'btn btn-primary']) ?>
        <button class="abtn btn">abc</button>
    </div>
    </div>
<style type="text/css">
    .form-query .row{
        /*margin-bottom: 20px;*/
    }

</style>
    <?php ActiveForm::end(); ?>

</div>
