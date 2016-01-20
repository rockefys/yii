<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web;
use common\services\TestService;
class SiteController extends Controller
{
    public function actionTest()
    {
        $test = TestService::rpc();//重写成单例模式
        $res = $test->ee(1,3,3,4,5); //重写__call，转化成 \common\test\ee，
                            //然后由client发送到API Gateway，实例化
        echo $res;
    }
}
