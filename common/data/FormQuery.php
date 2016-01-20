<?php

namespace common\data;

use yii;
/**
 * This is the ActiveQuery class for [[Atest]].
 *
 * @see Atest
 */
class FormQuery extends \yii\db\ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_INIT, [$this, 'getQuery']);
        $this->trigger(self::EVENT_INIT);
    }

    protected function getQuery()
    {
        $modelClass = $this->modelClass;
        $params = Yii::$app->request->post('query'); //列表页查询框都是动态生成的，名字都是query['abc']
        if(isset($params)) {
            $params = queryFilter($params); //去空处理
        } else {
            $params = [];
        }
        
        $get = Yii::$app->request->post('get');unset($get['p']);//获取链接中附加的查询条件，状态栏中的按钮url被附带了查询参数
        //将参数并入$params
        $get_len = count($get);
        for ($i = 0;$i < $get_len;++$i) {
            if((array_key_exists($get[$i], $query) || array_key_exists(str_replace('_1', '', $get[$i]), $query)) && !array_key_exists($get[$i], $params)) {
                $params[$get[$i]] = $get[++$i];
            }
        }
        $queryFields = $modelClass::queryFields();
        //query是查询条件生成的数组，从query中取出当前提交的查询条件。因此，如果提交了query定义之外的查询条件，是会被过滤掉的
        foreach ($queryFields as $key => $val) {
            if(!isset($params[$key]) && empty($val['required'])) {
                continue;
            } 
            if(!isset($params[$key]) && $val['required'] && isset($val['default'])) {
                $params[$key] = $val['default'];
            }
            if(!isset($val['field'])) {
                $val['field'] = $key;
            }
            if(!isset($val['query_type'])) {
                $val['query_type'] = 'eq';
            }
            //查询匹配方式
            switch ($val['query_type']) {
                case '':
                case 'eq':
                case 'neq':
                case 'lt':
                case 'lte':
                case 'gt':
                case 'gte':
                    $comparison = ['' => '=','eq'=>'=','neq'=>'!=','lt'=>'<','lte'=>'<=', 'gt'=>'>', 'gte'=>'>='];
                    $map[$key]= [$comparison[$val['query_type']], $val['field'], $params[$key]];
                    break;
                case 'in':
                    $map[$key] = [$val['query_type'],$val['field'], is_array($params[$key]) ? $params[$key] : explode(',', $params[$key])];
                    break;
                case 'like':
                    $map[$key]= [$val['query_type'], $val['field'], $params[$key]];
                    break;
                case 'between'://区间匹配
                    //边界值+1
                    if(is_array($params[$key]) && !empty(reset($params[$key])) && !empty(next($params[$key]))) {
                        $map[$key] = [ $val['query_type'], $val['field'], reset($params[$key]), next($params[$key]) ];
                    } elseif(!empty(reset($params[$key])) && empty(next($params[$key]))) {
                        $map[$key] = [ '>=', $val['field'], prev($params[$key])];
                    } elseif( empty(reset($params[$key])) && !empty(next($params[$key]))) {
                        $map[$key] = [ '<=', $val['field'], current($params[$key])];
                    }
                    break;
                case 'null':case 'notnull':
                    $operators = ['null'=> 'IS NULL', 'notnull'=>'IS NOT NULL'];
                    $map[$key]= $params[$key]. ' ' . $operators[$val['query_type']];
                    break;
                default:
                    if ($val['query_type'] instanceof Closure) {
                        $map[$key] = call_user_func($val['query_type'],$key, $val, $this);
                    }
                    break;
            }
            $this->andWhere($map[$key]);

        }
    
        //$params = I('q');//对状态栏的特殊处理,状态栏中的各种状态按钮实际上是附加了各种status=1 这样的查询条件
         if(!empty($params) && 0){
            $para=explode('&', urldecode($params));
            foreach ($para as $key => $v) {
                $cond=explode('=', $v);
                if(count($cond)===2)
                    $map[$table.'.'.$cond[0]]=$cond[1];
            }
        }
        if(!empty($map)) {
            dump($map);exit();
        }
    }

     /* 过滤条件
    // protect $filter = array(
    //     'status'=> array(
    //         '1' => '草稿',
    //         '2' => '已完成'
    //     ), 
    // );
    */
    //过滤函数，比如数据表中status值是1，2，3，列表页面中显示的是草稿、审核、已完成
    protected function filter_list(&$data, $filter = '', $reverse = false) {
        if(!is_array($data)) return;
        if(empty($filter)){
            if(empty($this->filter)) {
                $file = strtolower(CONTROLLER_NAME);
                $filter = C($file.'.filter');
            }
            else {
                $filter = $this->filter;
            }
        }
        //反向转换
        if($reverse) {
            $table = strtolower(CONTROLLER_NAME);
            foreach ($filter as $key => $val) {
                $val = array_flip($val);
                $filter[$table.'.'.$key] = $val ;
                unset($filter[$key]);
            }
        }
        else {
        }
        //二维数组
        if(is_array(reset($data))){
            foreach ($data as $key => $val) {
                foreach ($filter as $k => $v) {
                    if(!empty($v[$data[$key][$k]])) {
                        $data[$key][$k] = $v[$data[$key][$k]];
                    }
                }
            }
        }
        else{//一维数组
            foreach ($filter as $k => $v) {
                if(!empty($v[$data[$k]])) {
                    $data[$k] = $v[$data[$k]];
                }
            }
        }
    }

}