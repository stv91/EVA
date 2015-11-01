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
use yii\web\UploadedFile;

class SiteController extends Controller {
    
    public function behaviors() {
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
        if(Yii::$app->user->isGuest ) {
            return $this->redirect(['login']);
        }

        Yii::$app->params['current_page'] = "materials";

        if(Yii::$app->request->getIsPost()) {
            $subject = Yii::$app->request->post('subject');
            $file = $_FILES['materialFile'];
            $material = new Material();
            $material->setData($subject, $file);

            if($material->addMaterial()) {
                //$this->redirect(array('materials', 'm'=> $material->id));
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
    
    public function actionSearch_materials() {
        Yii::$app->params['current_page'] = "materials";

        $toSearch = json_decode(file_get_contents("php://input"));
        $degree = Yii::$app->session["currentDegree"];
        $search = Material::searchMaterial($toSearch->text, $toSearch->oficials, $toSearch->noOficials, $toSearch->course, $toSearch->subject, $degree);
        
        $result = array();
        foreach ($search as $key => $value) {
            $subject = Subject::getSubjectByCode($value['subject'])["name"];

            list($ano, $mes, $dia) = split("-", split(" ", $value["timestamp"])[0]);
            $data = array(
                'id' => $value["id"],
                'name' => $value["original_name"],
                'date' => "$dia/$mes/$ano",
                'type' => $value["type"]
            );
            
            if(isset($result[$subject])){
                array_push($result[$subject], $data);
            }
            else {
                $result[$subject] = array($data);
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return  $result;
        
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

    public function actionDeletematerial($id) {
        if(!Yii::$app->user->isGuest) {
            $material = $material = Material::find()->where(['id' => $id])->one();
            if($material->deleteMaterial(Yii::$app->user->identity->code)){
                return "OK";
            }
            else {
                return "ERROR";
            }
        }
        else {
            return "ERROR";
        }
    }

    public function actionGetsubjects() {
        $reuslt = "";
        if(!Yii::$app->user->isGuest) {
            $degree = Yii::$app->session["currentDegree"];
            $user = Yii::$app->user->identity->code;
            $isTeacher = Yii::$app->user->identity->isTeacher;
            $result = Subject::getSubjectsByUser($degree, $user, $isTeacher);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return  $result;
    }

    public function actionGetmaterial($id) {
        if(!Yii::$app->user->isGuest) {
            //$id = Yii::$app->request->get("id");

            $material  = Material::getMaterialByID($id);

            if(Yii::$app->user->identity->checkSubject($material["subject"])) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return  $material;
            }
        }
    }

    public function actionGetcurrentuser() {
        if(!Yii::$app->user->isGuest) {
            $code = Yii::$app->user->identity->code;
            Yii::$app->response->format = Response::FORMAT_JSON;
            return  $code;
        }
    }

    public function actionSavedescription() {
        if(!Yii::$app->user->isGuest) {
            $data = json_decode(file_get_contents("php://input"));
            $material = Material::find()->where(['id' => $data->id])->one();
            $material->description = $data->desc;
            $material->save();
        }
    }

    public function actionSavecomment() {
        if(!Yii::$app->user->isGuest) {
            $data = json_decode(file_get_contents("php://input"));
            $comment = new MaterialComment();
            $comment->user = Yii::$app->user->identity->code;
            $comment->is_teacher = Yii::$app->user->identity->isTeacher;
            $comment->content = $data->content;
            $comment->material = $data->id;
            if($data->reply != null) {
                $comment->reply = $data->reply;
            }
            $comment->save();
        }
    }

    public function actionGetcomments($id) {
        if(!Yii::$app->user->isGuest) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return  MaterialComment::getComments($id);
        }
    }

    public function actionPrueba() {
        Yii::$app->params['current_page'] = "exams";
        return $this->render('exams');
    }
}
