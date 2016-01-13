<?php
namespace common\events;

use yii\base\event;

class ParamEvent extends Event
{
    private $_param;

    public function getParam()
    {
        return $this->_param;
    }

    public function setParam($value)
    {
        $this->_param = $value;
    }
}