<?php

namespace app\models;

use Yii;
use common\models\ActiveQueryModelInterface;
/**
 * This is the model class for table "atest".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $textbox
 * @property string $password
 * @property string $select
 * @property string $textarea
 * @property string $datetime
 * @property boolean $checkbox
 * @property string $decimal
 * @property integer $smallint
 * @property string $tinytext
 * @property string $time
 */
class Atest extends \yii\db\ActiveRecord implements ActiveQueryModelInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'atest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'smallint'], 'integer'],
            [['select', 'textarea', 'tinytext'], 'string'],
            [['datetime', 'time'], 'safe'],
            [['checkbox'], 'boolean'],
            [['decimal'], 'number'],
            [['textbox', 'password'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    { 
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'textbox' => Yii::t('app', 'Textbox'),
            'password' => Yii::t('app', 'Password'),
            'select' => Yii::t('app', 'Select'),
            'textarea' => Yii::t('app', 'Textarea'),
            'datetime' => Yii::t('app', 'Datetime'),
            'checkbox' => Yii::t('app', 'Checkbox'),
            'decimal' => Yii::t('app', 'Decimal'),
            'smallint' => Yii::t('app', 'Smallint'),
            'tinytext' => Yii::t('app', 'Tinytext'),
            'time' => Yii::t('app', 'Time')
        ];
    }

    public function attributeDetails()
    {
        return [
            'id',
            'user_id',
            'textbox',
            'password',
            'select',
            'textarea:ntext',
            'datetime',
            'checkbox:boolean',
            'decimal',
            'smallint',
            'tinytext:ntext',
            'time',
        ];
    }

    public static function attributeLists()
    {
        return [
            'id',
            'user_id',
            'textbox',
            'password',
            'select',
            'datetime',
            'checkbox:boolean',
            'decimal',
        ];
    }

    public function controlTypes ()
    {
        return [
        //'text|textarea|select|checkbox|date|datetime|time|refer|list|func',
            'id'        => '',
            'user_id'   => '',
            'textbox'   => ['text'],
            'password'  => ['password'],
            'select'    => ['select','' ,[ 'a' => 'A', 'b' => 'B', 'c' => 'C' ], ['prompt' => '']],
            'textarea'  => ['textarea'],
            'datetime'  => ['datetime'],
            'checkbox'  => ['checkbox'],
            'decimal'   => ['number'],
            'smallint'  => ['list','',[ 'a' => 'A', 'b' => 'B', 'c' => 'C', ],[]],
            'tinytext'  => ['textarea'],
            'time'      => ['time'],
            'query'     => ['submit',Yii::t('app','Query'),['class'=> 'btn btn-info']]
        ];
    }

    public static function queryFields()
    {
        return [
            'id' => '',
            'textbox' => [],
            'select' => [],
            'datetime' => [],
            'decimal' => [],
            'time' => [],
            'user_id' => array(
                'query_type' => 'between',//|between|like|in|lt|gt|gte|lte|exists|null|notnull|func
                'field'     => 'user_id',
                'required'  => false,
                'default'   => 1,
                'join'      => []
            ),
            'query' => []
        ];
    }

    /**
     * @inheritdoc
     * @return AtestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\data\FormQuery(get_called_class());
    }
}
