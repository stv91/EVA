<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class MaterialComment extends ActiveRecord {


	public static function getReplies($id) {
		$query = "select id, is_teacher from material_comment where reply = $id order by date desc;";

		$result = Yii::$app->db->createCommand($query)->queryAll();

		$comments = array();
		for ($i = 0; $i < count($result); $i++) {
			$id = $result[$i]["id"];
			$isTeacher = $result[$i]["is_teacher"];
			if($isTeacher == 0) {
				$query = 	"select mc.id, mc.material,  mc.content,  DATE_FORMAT(mc.date, '%d/%m/%Y %H:%i') as date, mc.is_teacher, s.name, s.surname, mc.is_teacher
							 from material_comment mc, student s where  mc.user = s.code and mc.id = $id";
			}
			else {
				$query = 	"select mc.id, mc.material,  mc.content,  DATE_FORMAT(mc.date, '%d/%m/%Y %H:%i') as date, mc.is_teacher, t.name, t.surname, mc.is_teacher
							 from material_comment mc, teacher t where mc.user = t.code and mc.id = $id";
			}

			$comment = Yii::$app->db->createCommand($query)->queryOne();
			array_push($comments, $comment);
		}

		return $comments;
	}

	public static function getComments($material){
		$query = "select id, is_teacher from material_comment where material = $material and reply is null order by date desc;";

		$result = Yii::$app->db->createCommand($query)->queryAll();

		$comments = array();
		for ($i = 0; $i < count($result); $i++) {
			$id = $result[$i]["id"];
			$isTeacher = $result[$i]["is_teacher"];
			if($isTeacher == 0) {
				$query = 	"select mc.id, mc.material,  mc.content,  DATE_FORMAT(mc.date, '%d/%m/%Y %H:%i') as date, mc.is_teacher, s.name, s.surname, mc.is_teacher
							 from material_comment mc, student s where mc.user = s.code and mc.id = $id";
			}
			else {
				$query = 	"select mc.id, mc.material,  mc.content,  DATE_FORMAT(mc.date, '%d/%m/%Y %H:%i') as date, mc.is_teacher, t.name, t.surname, mc.is_teacher
							 from material_comment mc, teacher t where mc.user = t.code and mc.id = $id";
			}

			$comment = Yii::$app->db->createCommand($query)->queryOne();
			$comment["replies"] = self::getReplies($id);
			array_push($comments, $comment);
		}

		return $comments;
	}
}