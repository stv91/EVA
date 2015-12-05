<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\LoginForm;
use app\models\User;
use app\models\Material;
use app\models\MaterialComment;
use app\models\Subject;
use app\models\Exam;
use yii\web\UploadedFile;

class SiteController extends Controller {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index', 'materials', 'exams', 'messages', 'deadlines', 'notes'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'materials', 'exams', 'messages', 'deadlines', 'notes'],
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

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex() {
        Yii::$app->params['current_page'] = "index";
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        else {
            if(Yii::$app->request->getIsPost()){
                $degree = Yii::$app->request->post()["degree"];
                if($degree){
                    Yii::$app->session["currentDegree"] = $degree;
                    return $this->redirect(["//materials/index"]);
                }
            }
            return $this->redirect(["//materials/index"]);
        }
    }

    public function actionLogin() {
        Yii::$app->params['current_page'] = "login";
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if(Yii::$app->request->getIsPost()){
            Yii::$app->response->format = 'json';
            if ($model->load(Yii::$app->request->post())) {
                if($model->login())
                    return ["url" => Url::previous()];
                else
                    return $model->getErrors();
            }
            else
                return [];
        }
        else {
            return $this->render('login', ['model' => $model]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
