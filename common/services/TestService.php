<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\services;

use Yii;
use Closure;
use yii\base\Behavior;
use yii\base\Event;
use Hprose\Yii\Service;
use yii\web\Controller;
use common\behaviors\RpcBehavior;
class TestService extends Controller
{
	const EVENT_INIT = 'init';
    public function behaviors()
    {
        return [
            'rpc' => [
                'class' => RpcBehavior::className()
            ]
        ];
    }
    public function init()
    {
    	parent::init();
    	$this->trigger(self::EVENT_INIT);
    }
    public static function rpc()
    {
        return RpcBehavior::rpc(get_called_class());
    }

}