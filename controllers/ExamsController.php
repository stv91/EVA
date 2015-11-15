<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Exam;
use app\models\ExamQuestion;
use app\models\Mark;

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
                                      'editquestion',
                                      'getexams',
                                      'addquestion',
                                      'getquestiondata',
                                      'correctexam',
                                      'manageexam',
                                      'deleteexam',
                                      'updateexam',
                                      'getquestions',
                                      'questions',
                                      'deletequestion'
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
                    'createquestions' => ['post'],
                    'addquestion' => ['post'],
                    'doexam' => ['post'],
                    'correctexam' => ['post'],
                    'manageexam' => ['post'],
                    'deleteexam' => ['post'],
                    'updateexam' => ['post'],
                    'questions' => ['post'],
                    'getquestions' => ['post'],
                    'deletequestion' => ['post'],
                    'editquestion' => ['post'],
                    'getquestiondata' => ['post'],
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

    public function actionGetexams() {
        $user = Yii::$app->user->identity->code;
        $degree = Yii::$app->session["currentDegree"];

        if(Yii::$app->user->identity->isTeacher == 0) {
            $exams = Exam::getStudentExams($user, $degree);
        }
        else {
            $exams = Exam::getTearcherExams($user, $degree);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return  $exams;
    }

    public function actionCreatequestions() {
        Yii::$app->params['current_page'] = "exams";

        $exam = Yii::$app->request->post("id");
        $user = Yii::$app->user->identity->code;
        $isTeacher = Yii::$app->user->identity->isTeacher;
        if(Exam::checkExam($exam, $user, $isTeacher)) {
            $title = Yii::$app->user->identity->isTeacher == 0? "Proponer preguntas" : "Añadir preguntas";
            return $this->render('createQuestions', ["exam" => $exam, "title" => $title]);
        }
        return $this->redirect(['index']);
    }

    public function actionEditquestion() {
        Yii::$app->params['current_page'] = "exams";

        $q = Yii::$app->request->post("id");
        $question = ExamQuestion::find()->where(['id' => $q])->one();
        $user = Yii::$app->user->identity;

        if($user->isTeacher == 1 && isset($question) 
            && Exam::checkExam($question->exam, $user->code, $user->isTeacher)) {
            return $this->render('createQuestions', ["exam" => $question->exam, "title" => "Editar pregunta", "question" => $question->id]);
        }
        return $this->redirect(['index']);
    }

    public function actionGetquestiondata() {
        $q = Yii::$app->request->post("id");
        $question = ExamQuestion::find()->where(['id' => $q])->one();
        $user = Yii::$app->user->identity;

        if(isset($question) && Exam::checkExam($question->exam, $user->code, $user->isTeacher)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $question;
        }
    }

    public function actionAddquestion($exam) {
        $data = json_decode(file_get_contents("php://input"));
        $q = Yii::$app->request->get("question");

        $question = new ExamQuestion();
        if(isset($q)){
            $question = ExamQuestion::find()->where(['id' => $q])->one();
        }
        
        if(isset($question)) {
            $question->exam = $exam;
            $question->user = Yii::$app->user->identity->code;
            $question->question = $data->question;
            $question->correct_answer = $data->correctAnswer;
            $question->answer1 = $data->answer1 == ""? null : $data->answer1;
            $question->answer2 = $data->answer2 == ""? null : $data->answer2;
            $question->answer3 = $data->answer3 == ""? null : $data->answer3;

            if(Yii::$app->user->identity->isTeacher) {
                $question->validated = 1;
            }

            if($question->save()) {
                return "OK";
            }
        }
        
        return "ERROR";
    }

    private function reorderAnswers($question) {
        $dbAnswers = [
                        ["id" => 0, "text" => $question->correct_answer],
                        ["id" => 1, "text" => $question->answer1],
                    ];

        if($question->answer2 != null) {
            array_push($dbAnswers, ["id" => 2, "text" => $question->answer2]);
        }
        if($question->answer3 != null) {
            array_push($dbAnswers, ["id" => 3, "text" => $question->answer3]);
        }

        $usedIndex = [];
        $answers = [];
        while(count($answers) < count($dbAnswers)) {
            $randIndex = rand(0, count($dbAnswers)-1);
            while(in_array($randIndex, $usedIndex)) {
                $randIndex = rand(0, count($dbAnswers)-1);
            }
            array_push($usedIndex, $randIndex);
            array_push($answers, $dbAnswers[$randIndex]);
        }
        return ["id" => $question->id, "question" => $question->question, "answers" => $answers];
    }

    private function generateExam($exam) {
        $numQuestions = Exam::find()->where(['id' => $exam])->one()->num_questions;
        $exams = ExamQuestion::find()->where(['exam' => $exam, 'validated' => 1])->all();
        $usedIndex = [];
        $result = [];
        while(count($usedIndex) < $numQuestions && count($usedIndex) < count($exams)) {
            $randIndex = rand(0, count($exams)-1);
            while(in_array($randIndex, $usedIndex)) {
                $randIndex = rand(0, count($exams)-1);
            }
            array_push($usedIndex, $randIndex);
            array_push($result, $this->reorderAnswers($exams[$randIndex]));
        }

        return $result;
    }

    public function actionDoexam() {
        Yii::$app->params['current_page'] = "exams";
        $id = Yii::$app->request->post("exam");
        if(isset($id)) {
            $examInfo = Exam::getExamInfo($id);
            $questions = $this->generateExam($id);
            return $this->render("doExam", ["questions" => $questions, "examInfo" => $examInfo]);
        }
        else {
            return $this->redirect(['index']);
        }
    }

    private function correctExam($exam, $answers) {
        $numQuestions = Exam::find()->where(['id' => $exam])->one()->num_questions;
        $numAux = count(ExamQuestion::find()->where(['exam' => $exam, 'validated' => 1])->all());
        if($numAux < $numQuestions) {
            $numQuestions = $numAux;
        }
        $correctAnswer = 0;
        foreach ($answers as $question => $answer) {
            if($answer == "0"){
                $correctAnswer++;
            }
        }

        return round($correctAnswer * 10.0 / $numQuestions, 2);
    }

    private function inTimeRange($exam) {
        $exam = Exam::find()->where(['id' => $exam])->one();
        if(!isset($exam)) {
            return false;
        }

        $start = strtotime($exam->date);
        $duration =  strtotime($exam->duration);
        
        $finish = strtotime("+". date("H", $duration) ." hours", $start);
        $finish = strtotime("+". date("i", $duration) ." minutes", $finish);
        
        $now = strtotime("now");

        return $now >= $start && $now <= $finish;
    }

    private function createMark($exam, $numMark) {
        $mark = new Mark();
        $mark->student = Yii::$app->user->identity->code;
        $mark->exam = $exam;
        $mark->value = $numMark;

        return $mark->save();
    }

    public function actionCorrectexam($exam) {
        if($this->inTimeRange($exam)) {
            $mark = $this->correctExam($exam, Yii::$app->request->post());

            if($this->createMark($exam, $mark)) {
                return $mark;
            }
            else {
                return "Error";
            }
        }
        else {
            return "Error";
        }
    }

    public function actionManageexam() {
        Yii::$app->params['current_page'] = "exams";
        $exam = Yii::$app->request->post("id");
        $user = Yii::$app->user->identity;

        if(isset($exam)) { //Editar
            $isTeacher = Yii::$app->user->identity->isTeacher;
            if(Exam::checkExam($exam, $user->code, $isTeacher)) {
                return $this->render('manageExam', ["title" => "Modificar Examen", "exam" => $exam]);    
            }
            return $this->redirect(['index']);
        }
        else if($user->isTeacher == 1){ //Nuevo
            return $this->render('manageExam', ["title" => "Nuevo Examen", "exam" => "null"]);  
        }
        else {
            return $this->redirect(['index']);
        }
    }

    public function actionDeleteexam() {
        $exam = Yii::$app->request->post("id");
        $user = Yii::$app->user->identity->code;
        $isTeacher = Yii::$app->user->identity->isTeacher;
        if(Exam::checkExam($exam, $user, $isTeacher)) {
            $exam = Exam::find()->where(['id' => $exam])->one();
            if($exam->delete())
                return "OK";
            return "ERROR";
        }
        return "ERROR";
    }

    function updateExam($data){
        $exam = Exam::find()->where(["id" => $data->exam])->one();
        if($exam == null) {
            $exam = new Exam();
        }
        $exam->subject = $data->subject;
        $parts = explode("/", $data->date);
        $exam->date = $parts[2] . "-" . $parts[1] . "-". $parts[0] . " " . $data->startTime.":00";
        $exam->duration = $data->duration.":00";
        $exam->num_questions = $data->numQuestions;
        $exam->description = $data->description;
        $exam->student_questions = $data->studentQuestions? 1 : 0;
        return $exam->save();
    }

    public function actionUpdateexam() {
        $data = json_decode(file_get_contents("php://input"));
        error_log(file_get_contents("php://input"));
        if($this->updateExam($data)) {
            return "OK";
        }
        return "ERROR";
    }

    public function actionQuestions() {
        Yii::$app->params['current_page'] = "exams";
        $exam = Yii::$app->request->post("id");
        $user = Yii::$app->user->identity;
        if(isset($exam)) { 
            $isTeacher = Yii::$app->user->identity->isTeacher;
            if(Exam::checkExam($exam, $user->code, $isTeacher)) {
                return $this->render('questions', ["exam" => $exam]);
            }
            return $this->redirect(['index']);    
        }
        return $this->redirect(['index']);
    }

    public function actionGetquestions() {
        $exam = Yii::$app->request->post("id");
        $user = Yii::$app->user->identity;
        if(isset($exam)) { 
            $isTeacher = Yii::$app->user->identity->isTeacher;
            if(Exam::checkExam($exam, $user->code, $isTeacher)) {
                $exams = ExamQuestion::find()->where(["exam" => $exam])->all();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $exams;
            }
            return $this->redirect(['index']);    
        }
        return $this->redirect(['index']);
    }

    private function checkQuestionDelete($q) {
        $user = Yii::$app->user->identity;
        if($user->isTeacher == 0)
            return false;

        $question = ExamQuestion::find()->where(["id" => $q])->one();
        if(!$question)
            return false;

        return Exam::checkExam($question->exam, $user->code, $user->isTeacher);
    }

    public function actionDeletequestion() {
        $question = Yii::$app->request->post("id");
        if($this->checkQuestionDelete($question)) {
            $quest = ExamQuestion::find()->where(["id" => $question])->one();
            if($quest->delete())
                return "OK";
            return "ERROR";
        }
        return "ERROR";
    }
}