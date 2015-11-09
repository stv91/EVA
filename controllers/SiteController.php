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
        if(Yii::$app->user->isGuest) 
            return $this->redirect(['login']);
        else {
            if(Yii::$app->request->getIsPost()){
                $degree = Yii::$app->request->post()["degree"];
                if($degree){
                    Yii::$app->session["currentDegree"] = $degree;
                    return $this->render('index');
                }
            }
            return $this->render('index');
        }
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
    }*/

    public function actionMaterials() {
        Yii::$app->params['current_page'] = "materials";

        if(Yii::$app->request->getIsPost()) {
            $subject = Yii::$app->request->post('subject');
            $file = $_FILES['materialFile'];
            $material = new Material();
            $material->setData($subject, $file);

            if($material->addMaterial()) {
                return $this->render('materials', ["materialID" => $material->id]);
            }
            else {
                $message = "No se ha podido guardar el archivo.";
                if(!empty($material->getErrors())) {
                    $message = array_values($material->getErrors())[0][0];
                }
                return $this->render('error', ['message' =>  $message, 'name' => "Error subiendo el material"]);
            }
        }
        return $this->render('materials');
    }
    
 
    public function actionMessages() {
        Yii::$app->params['current_page'] = "messages";
        return $this->render('messages');
    }
    
    public function actionExams() {
        Yii::$app->params['current_page'] = "exams";
        return $this->render('exams');
    }
    
    public function actionDeadlines() {
        Yii::$app->params['current_page'] = "deadlines";
        return $this->render('deadlines');
    }
    
    public function actionNotes() {
        Yii::$app->params['current_page'] = "notes";
        return $this->render('notes');
    }
}
