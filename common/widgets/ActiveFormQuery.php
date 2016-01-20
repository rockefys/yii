<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;
use yii\widgets\ActiveForm;
use yii\base\InvalidCallException;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveFormQuery extends Widget
{
   
    public function init()
    {
        if ($this->model !== null) {
            $this->controls = $this->getControlReflection();
            $this->controlTypes = $this->model->controlTypes();
            $this->labels = $this->model->attributeLabels();
            $this->queryFields = $this->model->queryFields();
        }
        if ($this->queryFields === null) {
            throw new InvalidConfigException('The "queryFields" property must be set.');
        }
        if ($this->controlTypes === null) {
            throw new InvalidConfigException('The "controlTypes" property must be set.');
        }
        if ($this->labels === null) {
            throw new InvalidConfigException('The "labels" property must be set.');
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->lgCol = intval($this->fluid / $this->cols);
        $this->mdCol = $this->lgCol;
        $this->smCol = $this->lgCol * 2;
        $this->xsCol = $this->fluid;
        $this->colClass = 'col-lg-'. $this->lgCol . ' ' .'col-md-'. $this->mdCol . ' ' . 'col-sm-'. $this->smCol . ' ' . 'col-xs-'. $this->xsCol;
        echo Html::beginTag('div',['class' => Yii::$app->controller->id.'-query']);
        ActiveForm::begin(['options'=>['class'=>'form-inline form-query'],'enableClientValidation' => false]);
        
    }

    public function getControlReflection()
    {
        //text|textarea|select|checkbox|date|datetime|time|refer|list|func
        return [
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
            'number'   => 'number',
            'time'      => 'time',
            'refer'     => 'refer',
            'func'      => 'textInput',
            'tag'       => 'tag',
            'email'     => 'email',
            'button'    => 'button',
            'submit'    => 'submitInput',
            'reset'     => 'resetInput'

        ];
    }
    public $model;
    public $labels;
    public $queryFields;
    public $controlTypes;
    public $controls;
    public $options;

    public $cols = 4;
    public $fluid = 12;
    public $lgCol;
    public $mdCol;
    public $smCol;
    public $xsCol;
    public $colClass;

    public function renderFields()
    {
        $i = 0;
        $controlTypes = $this->controlTypes;
        $model = $this->model;
        $modelName = $model::className();
        foreach ($this->queryFields as $key => $val) {
            $again = false;
        again:
            if($i % $this->cols == 0) {
                echo Html::beginTag('p');
                echo Html::beginTag('div', ['class' => 'row']);
            }
            echo Html::beginTag('div', ['class' => 'form-group query-'. $modelName . '-' . $key . ' ' . $this->colClass]);
            echo Html::beginTag('div', ['class' => 'input-group col-lg-12 col-md-12 col-sm-12 col-xs-12']);
            $this->renderField($key,$val);
            echo Html::endTag('div');
            echo Html::endTag('div');
            ++$i;
            if(($i % $this->cols == 0) && $i != 0 || $i == count($this->queryFields)) {
                echo Html::endTag('div');
                echo Html::endTag('p');
            }
            if(isset($val['query_type']) && $val['query_type'] == 'between' && !$again) {
                $again = true;
                goto again;
            } 
        }
    }

    public function renderField($key, $val)
    {
        $options = ['class' => 'form-control'];
        if($this->controlTypes[$key] == '') {
            $this->controlTypes[$key] = [];
        }
        $element = empty($this->controlTypes[$key]) ? 'text' : current($this->controlTypes[$key]);
        if(!empty($element)) {
            $control = $this->controls[$element];
        }
        
        if(isset($val['query_type']) && $val['query_type'] != 'between') {
            $name = 'query[' . $key . ']';
        } else {
            $name = 'query[' . $key . '][]';
        }

        switch ($element) {
            case 'text':case 'hidden':case 'password':case 'file':
            case 'textarea':case 'checkbox':case 'radio':
                echo Html::Tag('span', $this->labels[$key] ,['class' => 'input-group-addon']);
                $options = ['class' => 'form-control'];
                echo Html::$control(
                    $name,
                    next($this->controlTypes[$key]),    //val
                    array_merge($options, empty(next($this->controlTypes[$key])) ? [] :current($this->controlTypes[$key]))     //options
                );
                break;
            case 'select':case 'list':
            case 'checkboxList':case 'radioList':case 'textarea':
                echo Html::Tag('span', $this->labels[$key] ,['class' => 'input-group-addon']);
                echo Html::$control(
                    $name,
                    next($this->controlTypes[$key]),     //val
                    next($this->controlTypes[$key]),    //content
                    array_merge($options, empty(next($this->controlTypes[$key])) ? [] :current($this->controlTypes[$key]))     //options
                );
                break;
            case 'refer':
                echo Html::Tag('span', $this->labels[$key] ,['class' => 'input-group-addon']);
                $options = ['class' => 'form-control','readonly'=>'true'];
                echo Html::textInput(
                    'query[' . $key . '_text][]',
                    '',
                    $options
                );
                echo Html::hiddenInput(
                    $name,
                    '',
                    $options
                );
                echo Html::beginTag('span', ['class' => 'input-group-btn']);
                echo Html::button(
                    Html::tag('span', '', ['class'=>'glyphicon glyphicon-search','aria-hidden'=>'true']),    //content
                    ['class'=>'btn btn-default']    //options
                );
                echo Html::endTag('span');
                
                break;
            case 'tag':
                echo Html::$control(
                    next($this->controlTypes[$key]),
                    next($this->controlTypes[$key]),    //content
                    next($this->controlTypes[$key])     //options
                );
                break;
            case 'email':case 'url':case 'number':case 'range':
            case 'datetime':case 'datetime-local':case 'search':
            case 'date':case 'month':case 'week':case 'time':
                echo Html::Tag('span', $this->labels[$key] ,['class' => 'input-group-addon']);
                $options = ['class' => 'form-control'];
                echo Html::input(
                    $control,
                    $name,
                    next($this->controlTypes[$key]),    //val
                    array_merge(empty(next($this->controlTypes[$key]))? [] :current($this->controlTypes[$key]), $options)     //options
                );
                break;
            case 'button':case 'submit':case 'reset':
                #echo Html::beginTag('span', ['class' => 'input-group-btn']);
                echo Html::$control(
                    empty(next($this->controlTypes[$key]))?(array_key_exists($key, $this->labels)?$this->labels[$key]:$key):current($this->controlTypes[$key]),
                    array_merge($options, empty(next($this->controlTypes[$key])) ? [] :current($this->controlTypes[$key]))    //options
                );
                #echo Html::endTag('span');
                break;
            case 'label':case 'a':case 'mailto':
                #$option = ;
                echo Html::$control(
                    next($this->controlTypes[$key]),    //content
                    next($this->controlTypes[$key]),    
                    array_merge($options, empty(next($this->controlTypes[$key])) ? [] :current($this->controlTypes[$key]))     //options
                );
                break;
            default:
                echo Html::Tag('span', $this->labels[$key] ,['class' => 'input-group-addon']);
                $options = ['class' => 'form-control'];
                echo Html::textInput(
                    $name,
                    next($this->controlTypes[$key]),    //val
                    array_merge($options, empty(next($this->controlTypes[$key])) ? [] :current($this->controlTypes[$key]))     //options
                );
                break;
        }
        
    }

    /**
     * Runs the widget.
     * This registers the necessary javascript code and renders the form close tag.
     * @throws InvalidCallException if `beginField()` and `endField()` calls are not matching
     */
    public function run()
    {
        /*
        if ($this->enableClientScript) {
            $view = $this->getView();
            ActiveFormAsset::register($view);
            $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        }
        */
        $this->renderFields();
        echo Html::endForm();
    }
}
