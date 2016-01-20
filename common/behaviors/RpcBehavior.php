<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\behaviors;

use Yii;
use Closure;
use yii\base\Behavior;
use yii\base\Event;
use Hprose\Yii\Service;
use common\Lib\RpcClient;
class RpcBehavior extends Behavior
{
    public $server;
    public $url;
    const EVENT_INIT = 'init';
    public function events()
    {
        return [
            self::EVENT_INIT => 'server'
        ];
    }

    public static function rpc($url)
    {
        $server = 'http://127.0.0.1/index.php?r=';
        return new RpcClient($server . str_replace('\\', '/', $url));
    }
    public function server(){
        $this->server = new Service();
        $methods = $this->getMethods();
        $this->registerFunction($methods);
        return $this->server->handle(Yii::$app);
    }

    protected function getMethods(){
        $methods = get_class_methods($this);
        $inherentsMethods = array(
            '_initialize','__construct','getActionName','isAjax','display','show','fetch','theme',
            'buildHtml','assign','__set','get','__get','__isset',
            '__call','error','success','ajaxReturn','redirect','__destruct','_empty','__hack_module','__hack_action'
        );
        $class = new \ReflectionClass($this);
        foreach ($methods as $methodName){
            if(!in_array($methodName, $inherentsMethods)){
                $func =  $class->getMethod($methodName);
                if($func->isPublic() && !$func->isProtected()) {
                    $methodFullName = str_replace('\\', '.', $this::className() .'\\'.$methodName);
                    $customerMethods[] = [$methodName, $this, $methodFullName];
                }
            }
        }
        return $customerMethods;
    }

    protected function registerFunction($funcs)
    {
        foreach ($funcs as $key => $val) {
            $type = count($val);
            if($type == 2) {
                $this->server->addMethod(reset($val), next($val));
            } elseif($type == 3) {
                $this->server->addMethod(reset($val), next($val), next($val));
            } elseif($type == 1) {
                $this->server->addFunction(reset($val));
            }
        }
    }
}
