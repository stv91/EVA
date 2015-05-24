<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use  yii\helpers\Url;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

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
            ]
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) 
            return $this->redirect(['login']);
        else
            return $this->render('index');
    }

    /*public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }*/
    public function actionLogin()
    {
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

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

   /* public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionPrueba(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        //return User::getUserByEmail("david.montoya@ua.es");
        return User::findIdentityByAccessToken("ffd43ca32e27a35598e8e8bea9dfce37c501c54bf53ad2887ecfa0873b1d0a98");
    }*/
    public function actionMaterials(){
        Yii::$app->params['current_page'] = "materials";
        return $this->render('materials');
    }
    
    public function actionMessages(){
        Yii::$app->params['current_page'] = "messages";
        return $this->render('messages');
    }
    
    public function actionExams(){
        Yii::$app->params['current_page'] = "exams";
        return $this->render('exams');
    }
    
    public function actionDeadlines(){
        Yii::$app->params['current_page'] = "deadlines";
        return $this->render('deadlines');
    }
    
    public function actionNotes(){
        Yii::$app->params['current_page'] = "notes";
        return $this->render('notes');
    }
}
