<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Deadline;
use app\models\DeadlineSubmit;

class DeadlinesController extends Controller {

	public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [  'index',
                                        'getstudentdeadlines',
                                        'uploadfile',
                                        'getteacherdeadlines',
                                        'deletedeadline',
                                        'managedeadline',
                                        'updatedeadline',
                                        'getdeadline'
                                     ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'deletedeadline' => ['post'],
                    'updatedealine' => ['post'],
                    'managedeadline' => ['post'],
                    'getdeadline' => ['post']
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
		Yii::$app->params['current_page'] = "deadlines";

        $user = Yii::$app->user->identity;
        if($user->isTeacher == 0)
            return $this->render('student');
        return $this->render('teacher');
	}

    public function actionGetstudentdeadlines() {
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 0){
            $degree = Yii::$app->session["currentDegree"];

            Yii::$app->response->format = Response::FORMAT_JSON;
            return Deadline::getDeadlinesByStudent($user->code, $degree);
        }
    }

    public function actionUploadfile() {
        if(Yii::$app->user->identity->isTeacher == 0) {
            $deadline = Yii::$app->request->post('id');
            $student = Yii::$app->user->identity->code;
            $file = $_FILES['file'];

            if(isset($deadline) && isset($student) && isset($file)) {
                $submit = new DeadlineSubmit();
                $submit->setData($deadline, $student, $file);
                if($submit->addSubmit()) {
                    return $this->redirect(['index']);
                }
                else {
                    $message = "No se ha podido guardar el archivo.";
                    if(!empty($submit->getErrors())) {
                        $message = array_values($submit->getErrors())[0][0];
                    }
                    return $this->render('//site/error', ['message' =>  $message, 'name' => "Error subiendo el archivo"]);
                }
            }
        }
         else {
            $message = "No se ha podido guardar el archivo.";
            if(!empty($submit->getErrors())) {
                $message = array_values($submit->getErrors())[0][0];
            }
            return $this->render('//site/error', ['message' =>  $message, 'name' => "Error subiendo el archivo"]);
        }
    }


    public function actionGetteacherdeadlines() {
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 1){
            $degree = Yii::$app->session["currentDegree"];

            Yii::$app->response->format = Response::FORMAT_JSON;
            return Deadline::getDeadlinesByTeacher($user->code, $degree);
        }
    }

    public function actionDeletedeadline() {
        $isTeacher = Yii::$app->user->identity->isTeacher;

        if($isTeacher == 1) {
            $id = Yii::$app->request->post("id");
            $user = Yii::$app->user->identity->code;
            $degree = Yii::$app->session["currentDegree"];

            if(Deadline::checkDeadline($id, $user, $degree, $isTeacher)) {
                $deadline = Deadline::find()->where(['id' => $id])->one();
                if($deadline->delete())
                    return "OK";
                return "ERROR";
            }
        }
        return "ERROR";
    }

    public function actionManagedeadline() {
        Yii::$app->params['current_page'] = "deadlines";
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 1){
            $deadline = Yii::$app->request->post('id');
            if(isset($deadline)) {
                $degree = Yii::$app->session["currentDegree"];
                if(Deadline::checkDeadline($deadline, $user->code, $degree, $user->isTeacher)) {
                    return $this->render('createDeadline', ['deadline' => $deadline, 'title' => 'Editar entrega']);
                }
                else {
                    return $this->render('index');
                }
            }
            return $this->render('createDeadline', ['deadline' => 'null', 'title' => 'Crear entrega']);
        }
        return $this->render('index');
    }

    private function updateDeadline($data) {
        $deadline = Deadline::find()->where(["id" => $data->id])->one();
        if($deadline == null) {
            $deadline = new Deadline();
        }

        $deadline->subject = $data->subject;
        $deadline->name = $data->name;
        $parts = explode("/", $data->date);
        $deadline->date = $parts[2] . "-" . $parts[1] . "-". $parts[0];
        $deadline->description = $data->description;

        return $deadline->save();
    }

    public function actionUpdatedeadline() {
        $data = json_decode(file_get_contents("php://input"));
        if($this->updateDeadline($data)) {
            return "OK";
        }
        return "ERROR";
    }

    public function actionGetdeadline() {
        $user = Yii::$app->user->identity;
        $degree = Yii::$app->session["currentDegree"];
        $deadline = Yii::$app->request->post('id');
        if($user->isTeacher == 1 
            && Deadline::checkDeadline($deadline, $user->code, $degree, $user->isTeacher)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Deadline::find()->where(['id' => $deadline])->one();
        }
    }
}