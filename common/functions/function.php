<?php

function kidding($event){
    dump("yes I'm kidding you.".$event->data);
}

/**
* 校验是否是日期
*/
function dateValid($str){
    $unixTime = strtotime($str);
    if (!$unixTime) {
        return false;
    }
    return true;
}

/**
 * 实例化模型类 格式 [资源://][模块/]模型
 * @param string $name 资源地址
 * @param string $layer 模型层名称
 * @return Yii\Model
 */
function D($name='',$layer='') {
    if(empty($name)) return new yii\base\Model;
    static $_model  =   array();
    $layer          =   '';
    if(isset($_model[$name.$layer]))
        return $_model[$name.$layer];
    //$class          =   parse_res_name($name,$layer);
    if(class_exists($class)) {
        $model      =   new $class(basename($name));
    }elseif(false === strpos($name,'/')){
        $class      =   '\\Yii\\'.$layer.'\\'.$name.$layer;
        $model      =   class_exists($class)? new $class($name) : new Think\Model($name);
    }else {
        Think\Log::record('D方法实例化没找到模型类'.$class,Think\Log::NOTICE);
        $model      =   new Think\Model(basename($name));
    }
    $_model[$name.$layer]  =  $model;
    return $model;
}

/**
 * 抛出异常处理
 * @param string $msg 异常消息
 * @param integer $code 异常代码 默认为0
 * @throws Think\Exception
 * @return void
 */
function E($msg, $code=0) {
    throw new Think\Exception($msg, $code);
}

//POST数据处理
//未输入值的txtbox为空，应当移除
//复选框未选择，则不会出现在post中，应添加条件并赋值为false
function queryFilter($data){
    foreach ($data as $key => $value) {
        if($value == '' || (is_array($value) && empty($value[1]))){
            unset($data[$key]);
        }
    }
    return $data;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}
