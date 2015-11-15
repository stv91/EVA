<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Mark;

class NotesController extends Controller {

	public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [  'index',
                                        'getstudentmarks'
                                     ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'index' => ['post'],
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
		Yii::$app->params['current_page'] = "notes";

        $user = Yii::$app->user->identity;
        if($user->isTeacher == 0)
            return $this->render('student');
        return $this->render('teacher');
	}

    public function actionGetstudentmarks() {
        $user = Yii::$app->user->identity;
        $degree = Yii::$app->session["currentDegree"];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return Mark::getMarksByStudent($user->code, $degree);
    }
}