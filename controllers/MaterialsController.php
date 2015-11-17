<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\User;
use app\models\Material;
use app\models\MaterialComment;
use app\models\Subject;
use yii\web\UploadedFile;

class MaterialsController extends Controller {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [  'index',
                                        'deletematerial',
                                        'searchmaterials', 
                                        'getsubjects', 
                                        'getmaterial', 
                                        'getcurrentuser',
                                        'savedescription',
                                        'savecomment',
                                        'getcomments'
                                    ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'deletematerial' => ['post'],
                    'searchmaterials' => ['post'],
                    'getmaterial' => ['post'],
                    'savedescription' => ['post'],
                    'savecomment' => ['post'],
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
                return $this->render('//site/error', ['message' =>  $message, 'name' => "Error subiendo el material"]);
            }
        }
        return $this->render('materials');
    }

    public function actionDeletematerial($id) {
        $material = Material::find()->where(['id' => $id])->one();
        if($material->deleteMaterial(Yii::$app->user->identity->code)){
            return "OK";
        }
        else {
            return "ERROR";
        }
    }

    public function actionSearchmaterials() {
        Yii::$app->params['current_page'] = "materials";

        $toSearch = json_decode(file_get_contents("php://input"));
        $degree = Yii::$app->session["currentDegree"];
        $search = Material::searchMaterial($toSearch->text, $toSearch->oficials, $toSearch->noOficials, $toSearch->course, $toSearch->subject, $degree);
        
        $result = array();
        foreach ($search as $key => $value) {
            $subject = Subject::getSubjectByCode($value['subject'])["name"];
            
            if(isset($result[$subject])){
                array_push($result[$subject], $value);
            }
            else {
                $result[$subject] = array($value);
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return  $result;
        
    }

    public function actionGetsubjects() {
        $degree = Yii::$app->session["currentDegree"];
        $user = Yii::$app->user->identity->code;
        $isTeacher = Yii::$app->user->identity->isTeacher;
        $result = Subject::getSubjectsByUser($degree, $user, $isTeacher);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return  $result;
    }

    public function actionGetmaterial($id) {
        $material  = Material::getMaterialByID($id);

        if(Yii::$app->user->identity->checkSubject($material["subject"])) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return  $material;
        }
    }

    public function actionGetcurrentuser() {
        $code = Yii::$app->user->identity->code;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return  $code;
    }

    public function actionSavedescription() {
        $data = json_decode(file_get_contents("php://input"));
        $material = Material::find()->where(['id' => $data->id])->one();
        $material->description = $data->desc;
        $material->save();
    }

    public function actionSavecomment() {
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

    public function actionGetcomments($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return  MaterialComment::getComments($id);
    }
}