<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Mark;
use app\models\Exam;
use app\models\Deadline;
use app\models\Subject;

class NotesController extends Controller {

	public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [  'index',
                                        'getstudentmarks',
                                        'getexams',
                                        'getdeadlines',
                                        'showexammarks',
                                        'showdeadlinemarks',
                                        'changedeadlinemark',
                                        'changeexammark'
                                     ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'showexammarks' => ['post'],
                    'showdeadlinemarks' => ['post'],
                    'changedeadlinemark' => ['post'],
                    'changeexammark' => ['post']
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

    public function actionGetexams() {
        $user = Yii::$app->user->identity;
        $degree = Yii::$app->session["currentDegree"];
        if($user->isTeacher == 1) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Mark::getExamsDone($user->code, $degree);
        }
    }

    public function actionGetdeadlines() {
        $user = Yii::$app->user->identity;
        $degree = Yii::$app->session["currentDegree"];
        if($user->isTeacher == 1) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Mark::getDeadlineFinished($user->code, $degree);
        }
    }

    public function actionShowexammarks() {
        Yii::$app->params['current_page'] = "notes";
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 1) {
            $exam = Yii::$app->request->post('id');
            if(Exam::checkExam($exam, $user->code, $user->isTeacher)) {
                $ex = Exam::find()->where(['id' => $exam])->one();
                $subject = Subject::find()->where(['code' => $ex->subject])->one();

                $parts = explode('-', explode(' ', $ex->date)[0]);

                $marks = Mark::getAllExamMarks($exam);
                return $this->render('examMarks', [ 'marks' => $marks,
                                                    'title' => $subject->name,
                                                    'date' => "$parts[2]/$parts[1]/$parts[0]",
                                                    'exam' => $exam]);
            }
        }
        return $this->redirect('index');
    }

     public function actionShowdeadlinemarks() {
        Yii::$app->params['current_page'] = "notes";
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 1) {
            $deadline = Yii::$app->request->post('id');
            $degree = Yii::$app->session["currentDegree"];
            if(Deadline::checkDeadline($deadline, $user->code, $degree, $user->isTeacher)) {
                $dl = Deadline::find()->where(['id' => $deadline])->one();
                $subject = Subject::find()->where(['code' => $dl->subject])->one();

                $parts = explode('-', $dl->date);

                $marks = Mark::getAllDeadlineMarks($deadline);
                return $this->render('deadlineMarks', [ 'marks' => $marks,
                                                        'title' => $subject->name,
                                                        'name' => $dl->name,
                                                        'date' => "$parts[2]/$parts[1]/$parts[0]",
                                                        'deadline' => $deadline]);
            }
        }
        return $this->redirect('index');
    }

    public function actionChangedeadlinemark() {
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 1) {
            $student = Yii::$app->request->post('student');
            $work = Yii::$app->request->post('deadline');
            $newMark = Yii::$app->request->post('mark');
            $degree = Yii::$app->session["currentDegree"];

            if(Deadline::checkDeadline($work, $user->code, $degree, $user->isTeacher)) {
                $mark = Mark::find()->where(['student' => $student, 'work' => $work])->one();
                $mark->value = $newMark;
                if($mark->save()){
                    return "OK";
                }
            }
        }
        return "ERROR";
    }

    public function actionChangeexammark() {
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 1) {
            $student = Yii::$app->request->post('student');
            $exam = Yii::$app->request->post('exam');
            $newMark = Yii::$app->request->post('mark');

            if(Exam::checkExam($exam, $user->code, $user->isTeacher)) {
                $mark = Mark::find()->where(['student' => $student, 'exam' => $exam])->one();
                $mark->value = $newMark;
                if($mark->save()){
                    return "OK";
                }
            }
        }
        return "ERROR";
    }
}