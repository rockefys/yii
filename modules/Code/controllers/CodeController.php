<?php

namespace app\modules\code\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\PageCache;
use yii\filters\AccessControl;
use yii\db\DbDependency;
use yii\behaviors\TimestampBehavior;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\events\ParamEvent;


class CodeController extends Controller
{
    /*
    const EVENT_BEFORE_ADD      = 'event_before_add';
    const EVENT_BEFORE_VIEW     = 'event_before_view';
    const EVENT_BEFORE_EDIT     = 'event_before_edit';
    const EVENT_BEFORE_LIST     = 'event_before_list';
    const EVENT_BEFORE_SEARCH   = 'event_before_search';
    const EVENT_BEFORE_DELETE   = 'event_before_delete';
    const EVENT_BEFORE_IMPORT   = 'event_before_import';
    const EVENT_BEFORE_EXPORT   = 'event_before_export';

    const EVENT_AFTER_ADD       = 'event_after_add';
    const EVENT_AFTER_VIEW      = 'event_after_view';
    const EVENT_AFTER_EDIT      = 'event_after_edit';
    const EVENT_AFTER_LIST      = 'event_after_list';
    const EVENT_AFTER_SEARCH    = 'event_after_search';
    const EVENT_AFTER_DELETE    = 'event_after_delete';
    const EVENT_AFTER_IMPORT    = 'event_after_import';
    const EVENT_AFTER_EXPORT    = 'event_after_export';
    */
   
    public function behaviors()
    {
        return [
            
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function init() {
        parent::init();
        $this->on('EVENT_INIT', [$this, 'inited']);
        $this->trigger('EVENT_INIT');
    }

    protected $columns = array(
        'id' => '',
        'name' => '名称',
        'code' => ['type'=>'digit','title'=>'编号'],
        'user' => ['type'=>'link','title'=>'用户','link'=>'user/view/{{userid}}','content'=>'{{username}}'],
        'status' => '状态',
    );
    protected $query   = array (
        'status' => array(
            'title' => '状态',
            'query_type' => 'eq',
            'control_type' => 'select',
            'value' => array(
                'normal' => '正常',
                'unnormal' => '已关闭',
            ),
        ),
        'name'=>array(
            'title' => '名称',
            'query_type' => 'eq',
            'control_type' => 'text',
            'value' => '',
        ),
        'code' => array (
            'title' => '编号',
            'query_type' => 'like',
            'control_type' => 'text',
            'value' => '',
        ),
        'user_id' => array (
            'title' => '用户',
            'query_type' => 'eq',
            'control_type' => 'select',
            'value' => '',
            'autofill' => 'true',
            'class' => 'app\controllers\User::getAll'
        ),
    );
    //页面展示数据映射关系 例如取出数据是qualified 显示为合格
    protected $filter = array(
        'status' => array(
            'normal' => '合格',
            'unnormal' => '不合格',
        ),
    );

    private $_query = [];
    protected function getQuery()
    {
        return $this->_query;
    }
    protected function setQuery($query)
    {
        //根据query的格式进行数据初始化
    }

    private $_model = null;
    private $_modelName = null;
    protected function getModel()
    {
        if(empty($this->_model)) {
            if(empty($this->_modelName)){
                //当前类命名空间父级下的models
                $ns = dirname(str_replace('\\', '/', __NAMESPACE__));
                $ns = str_replace('/', '\\', $ns) . '\\models\\';
                $class = $ns . ucfirst($this->id);
                if(class_exists($class)) {
                    $this->_modelName = $class;
                } else {
                    //顶级命名空间下的的models
                    $ns = current(explode('\\', __NAMESPACE__)) . '\\models\\' . ucfirst($this->id); 
                    if($class !== $ns && class_exists($ns)) {
                        $this->_modelName = $ns;
                    } else {
                        throw new Exception("Model not found :" . $class, 1);
                    }
                }
            }
            $this->_model = new $this->_modelName();
        }
        return $this->_model;
    }
    
    protected function setModel($model)
    {
        //这里没有直接产生一个实例，是起到延迟加载的作用
        //并不是每次请求都需要new $model()
        $this->_modelName = $model;
    }
    
    public function inited()
    {
        //传入当前控制器对应model的完整限定名
        //用于单例model的实例化
        //默认为当前命名空间父级下的models中的同名model，即../models/*.php
        //$this->setModel('app\modules\code\models\Code');


        /*
        $this->on(self::EVENT_, [$this, '']);
        $this->on(self::EVENT_, [$this, '']);
        $this->on(self::EVENT_, [$this, '']);
        $this->on(self::EVENT_, [$this, '']);
        $this->on(self::EVENT_, [$this, '']);
        $this->on(self::EVENT_, [$this, '']);
        */
    }


    /* 过滤条件
    // protect $filter = array(
    //     'status'=> array(
    //         '1' => '草稿',
    //         '2' => '已完成'
    //     ), 
    //     'type' => array(
    //          '0' => '普通类型',
    //          '1' => '默认类型'
    //     ),
    //
    // );
    */
    //过滤函数，比如数据表中status值是1，2，3，列表页面中显示的是草稿、审核、已完成
    protected function filterList(&$data, $filter = '', $type = '0') {
        if(!is_array($data)){
            return;
        }
        if(empty($filter)){
            $filter = $this->filter;
        }
        //反向转换
        if($type == '1') {
            foreach ($filter as $key => $val) {
                $val = array_flip($val);
                $filter[$key] = $val ;
                unset($filter[$key]);
            }
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
        else{
        //一维数组
            foreach ($filter as $k => $v) {
                if(!empty($v[$data[$k]])) {
                    $data[$k] = $v[$data[$k]];
                }
            }
        }
    }

    public function actionSearch() {
        return $this->Query;
    }
    public function actionIndex()
    {
        if (!$this->before($this->action->id)) {
            //return false;
        }
        //$searchCondition = $this->search();
        $M = $this->getModel();
        $dataProvider = new ActiveDataProvider([
            'query' => $M::find(),
        ]);
        if($this->after($this->action->id)) {
            //return false;
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function search() {
        $this->before('search');
        $params = YII::$app->request->post('query');
        $get = YII::$app->request->get('q');

        $params = empty($params) ? [] : queryFilter($params);
        $paramsLen = count($params);
        if(!empty($get)){
            $para=explode('&', urldecode($get));
            foreach ($para as $k => $v) {
                $cond = explode('=', $v);
                if(count($cond) === 2 ) {
                    $get[$cond[0]] = $cond[1];
                }
            }
        }
        for ($i = 0;$i < $paramsLen;++$i) {
            if((array_key_exists($get[$i], $query) || array_key_exists(str_replace('_1', '', $get[$i]), $query)) && !array_key_exists($get[$i], $params)) {
                $params[$get[$i]] = $get[++$i];
            }
        }

        if(!empty($params)){
            foreach ($query as $key => $v) {//query是查询条件生成的数组，从query中取出当前提交的查询条件。因此，如果提交了query定义之外的查询条件，是会被过滤掉的
                if(!array_key_exists($key, $params) && !array_key_exists($key.'_1', $params)) {
                    continue;
                }
                //查询匹配方式
                switch ($v['queryType']) {
                    case 'eq'://相等
                        $map[$key]=array($v['queryType'],$params[$key]);
                        break;
                    case 'in':
                        $map[$key]=array($v['queryType'],$params[$key]);
                        break;
                    case 'like':
                        $map[$key]=array($v['queryType'],'%'.$params[$key].'%');
                        break;
                    case 'between'://区间匹配
                        //边界值+1
                        if(dateValid($params[$key]) && dateValid($params[$key.'_1'])){
                            $params[$key.'_1'] = date('Y-m-d',strtotime($params[$key.'_1'].' +1 days'));
                        }elseif(is_numeric($params[$key.'_1'])){
                            $params[$key.'_1'] = $params[$key.'_1'] + 1;
                        }
                        if(empty($params[$key]) && !empty($params[$key.'_1'])) {
                            $map[$key]=array('lt',$params[$key.'_1']);
                        }
                        elseif(!empty($params[$key]) && empty($params[$key.'_1'])) {
                            $map[$key]=array('gt',$params[$key]);
                        }
                        else {
                            $map[$key]=array($v['queryType'],$params[$key].','.$params[$key.'_1']);
                        }
                        break;
                }
            }

            $this->after('search', $map);//查询条件生成以后，这里可以往$map中加入新的查询条件
            return $map;
        }
    }

    public function actionView()
    {
        $M = $this->getModel();
        $pk = current($M->primaryKey());

        $id =  Yii::$app->request->get($pk);
        if(empty($id)) {
            //参数为空
        }
        $this->before($this->action->id, $id);

        $data['model'] = $this->findModel();
        if(empty($data['model'])) {

        }

        $this->after($this->action->id, $data);
        return $this->render('view', $data);
    }

    public function actionCreate()
    {
        $model = $this->getModel();
        $pk = current($model->primaryKey());
        if ($model->load(Yii::$app->request->post())) {
            $this->before('add', $model);
            $res = $model->save();
            if($res) {
                $this->after('add', $model);
            } else {
                $this->msgReturn(0, 'add error');
            }
            return $this->redirect(['view', $pk => $model->$pk]);
        } else {
            $this->msgReturn(0, 'parameters error');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel();
        $model->delete();

        return $this->redirect(['index']);
    }

    public function delete() {
        $M      =   $this->getModel();
        $pk     =   current($M->primaryKey());
        $ids    =   Yii::$app->request->post($pk);//要删除的主键列表，以逗号分割
        $ids    =   array_unique(array_filter(explode(',', $ids)));
        $this->before('delete');//删除前
        $map[$pk]   =   array('in', $ids);
        $data['is_deleted'] = 1;
        $res = $$M->where($map)->save($data);//逻辑删除
        if($res == true) {
            $this->after('delete', $ids);//删除后
        }
        $this->msgReturn($res);
    }

    protected function findModel()
    {
        $M      =   $this->getModel();
        $pk     =   current($M->primaryKey());
        $id     =   Yii::$app->request->get($pk);
        if (($model = $M::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function before($action)
    {
        $eventPrefix = 'EVENT_BEFORE_';
        $statePrefix = 'STATE_BEFORE_';

        $actionNameUpper = strtoupper($action);
        $eventName = $eventPrefix . $actionNameUpper;

        $args = func_get_args();
        $event = new ParamEvent;
        $event->Param = $args;
        $this->trigger($eventName, $event);
        return $event->handled;
    }

    protected function after($action)
    {
        $eventPrefix = 'EVENT_BEFORE_';
        $statePrefix = 'STATE_BEFORE_';

        $actionNameUpper = strtoupper($action);
        $eventName = $eventPrefix . $actionNameUpper;

        $args = func_get_args();
        $event = new ParamEvent;
        $event->Param = $args;
        $this->trigger($eventName, $event);
        return $event->handled;
    }
    protected function msgReturn($res, $msg='', $data = '', $url=''){
        $request = Yii::$app->request;
        $msg = empty($msg) ? ($res > 0 ? '操作成功' : '操作失败') : $msg;
        $res = array('status'=>$res,'msg'=>$msg,'data'=>$data,'url'=>$url);
        echo json_encode($res);
        return;
        if($request->isAjax){
            $this->ajaxReturn(array('status'=>$res,'msg'=>$msg,'data'=>$data,'url'=>$url));
        } elseif($res){
            
            $this->success($msg,$url);
        }
        else{
            $this->error($msg,$url);
        }
    }

    public function export()
    {

    }
    public function import()
    {

    }
    public function actionTest()
    {
        echo 'test';
    }

    public function transactions()
    {
        return [];
    }
    public function optimisticLock()
    {
        return null;
    }

}
