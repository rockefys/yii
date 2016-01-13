<?php

namespace app\modules\code\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class Code extends ActiveRecord
{
    public static function tableName()
    {
        return 'test';
    }
    public static function getDb()
    {
        return \Yii::$app->db;  // 使用名为 "db2" 的应用组件
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code' => '代码',
            'name' => '名称',
            'status' => '状态',
        ];
    }

    public function transactions() {
        return [
            'changeMobile' => self::OP_INSERT,
        ];
    }

    //关联操作只能保证这个函数的执行，但保证不了数据一致性，
    //数据一致性是靠上面的事务来支持的
    
}
