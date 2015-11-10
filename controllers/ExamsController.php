<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Exam;

class ExamsController extends Controller {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index',
                                      'doexam',
                                      'createquestions',
                                      'getexams'
                                     ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'getexams' => ['post'],
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
        Yii::$app->params['current_page'] = "exams";
        if(Yii::$app->user->identity->isTeacher == 0) {
            return $this->render('student');    
        }
        else {
            return $this->render('teacher');
        }
    }

    public function actionDoexam() {
        Yii::$app->params['current_page'] = "exams";
        return $this->render('doExam');
    }

    public function actionCreatequestions() {
        Yii::$app->params['current_page'] = "exams";
        return $this->render('createQuestions');
    }

    public function actionGetexams() {
        $user = Yii::$app->user->identity->code;
        $degree = Yii::$app->session["currentDegree"];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return  Exam::getStudentExams($user, $degree);
    }

    public function checkExam() {

    }
}