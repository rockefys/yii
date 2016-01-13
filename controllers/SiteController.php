<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web;
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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

    public function actionIndex()
    {
        return $this->render($this->action->id);
    }

    public function actionAbout()
    {
        return $this->render($this->action->id);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        return $this->render($this->action->id,
            ['model' => $model]
        );
    }
    public function actionLogin()
    {
        return $this->render($this->action->id,
            ['model' => new LoginForm()]
        );
    }
    public function actionTest()
    {
        sleep(5);
        //throw new NotFoundHttpException('429','');
        echo json_encode([]);
    }

}
