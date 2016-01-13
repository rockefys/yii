<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Atest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class=<?= Yii::$app->controller->id ?>"-form">

    <?php $form = ActiveForm::begin(); ?>
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
    foreach ($model->controlTypes() as $key => $val) {
        $field = $form->field($model, $key);
        $options = [];
        if(is_string($val)) {
            if(in_array($key, ['textbox','password','textarea'])) {
                $options['maxlength'] = true;
                $options['rows'] = 6;
            }
            echo $field->$controlTypes[$val]();
        } elseif(is_array($val)) {
            echo $field->$controlTypes[current($val)](next($val), next($val));
        }
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
