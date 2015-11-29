<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\models\ConversationUser;
use app\models\Conversation;
use app\models\Message;
use app\models\User;
use app\models\Subject;

class MessagesController extends Controller {

	public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [  'index', 
                                        'getconversations', 
                                        'sendmessage', 
                                        'getmessages',
                                        'leaveconversation',
                                        'getusersbysubject',
                                        'createconversation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'sendmessage' => ['post'],
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
		Yii::$app->params['current_page'] = "messages";
        return $this->render('messages');
	}

    public function actionGetconversations() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Conversation::getConversations(Yii::$app->user->identity->email);
    }

    public function actionSendmessage() {
        $data = json_decode(file_get_contents("php://input"));
        $msg = new Message();
        $msg->conversation = $data->conversation;
        $msg->user = Yii::$app->user->identity->email;
        $msg->text = $data->text;

        if(Conversation::checkConversation($msg->conversation, $msg->user)) {
            if($msg->save()) {
                return "OK";
            }  
        }
        return "ERROR";
    }

    public function actionGetmessages($id) {
        $email = Yii::$app->user->identity->email;
        if(Conversation::checkConversation($id, $email)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Message::getMessages($id);
        }
    }

    public function actionLeaveconversation($id) {
        $email = Yii::$app->user->identity->email;
        if(Conversation::checkConversation($id, $email)) {
            $cu = ConversationUser::find()->where(['conversation' => $id, 'user' => $email])->one();
            if($cu->delete()) {
                return "OK";
            }
        }
        return "ERROR";
    }

    public function actionGetusersbysubject($id) {
        $user = Yii::$app->user->identity;
        if(Subject::checkSubject($id, $user->code, $user->isTeacher)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return User::getUserBySubject($id, $user->email);
        }
    }

    public function actionCreateconversation() {
        $data = json_decode(file_get_contents("php://input"));

        $conv = new Conversation();
        $conv->subject = $data->subject;
        $conv->name = $data->name;
        $conv->save();

        $cv = new ConversationUser();
        $cv->conversation = $conv->id;
        $cv->user = Yii::$app->user->identity->email;

        $transaction = Yii::$app->db->beginTransaction();
        if($cv->save()) {
            $error = false;
            foreach ($data->users as $user) {
                $cv = new ConversationUser();
                $cv->conversation = $conv->id;
                $cv->user = $user->email;
                if(!$cv->save()) {
                    $error = true;
                    break;
                }
            }
            if($error) {
                $transaction->rollBack();
                return "ERROR";
            }
            $transaction->commit();
            return "OK";
        }
        else {
            $transaction->rollBack();
            return "ERROR";
        }
    }
}