<?php

namespace app\models;

use Yii;

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
class Atest extends \yii\db\ActiveRecord
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
            'time' => Yii::t('app', 'Time'),
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
            'id' => 'label',
            'user_id' => 'refer',
            'textbox' => 'text',
            'password' => 'password',
            'select' => ['select', [ 'a' => 'A', 'b' => 'B', 'c' => 'C', ], ['prompt' => '']],
            'textarea' => 'textarea',
            'datetime' => 'datetime',
            'checkbox' => 'checkbox',
            'decimal' => 'decimal',
            'smallint' => ['list',[ 'a' => 'A', 'b' => 'B', 'c' => 'C', ],[]],
            'tinytext' => 'textarea',
            'time' => 'time'
        ];
    }

    public static function queryFields()
    {
        return [
            'textbox' => [],
            'select' => [],
            'datetime' => [],
            'decimal' => [],
            'time' => [],
            'user_id' => array(
                'query_type' => 'eq|between|like|in|lt|gt|gte|lte|exists|null|notnull|func',
                'name'     => 'user_id',
                'required'  => true,
                'default'   => date('Y-m-d'),
                'filter'    => [1=> 123],
                'func'      => '',
                'join'      => [],
                'relation' => array(
                    'relation_type' => 'during',
                    'field'     => 'day',
                    'default' => 1
                ),
            )
        ];
    }

    /**
     * @inheritdoc
     * @return AtestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AtestQuery(get_called_class());
    }
}
