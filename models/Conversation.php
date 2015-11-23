<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\Utils;

class Conversation extends ActiveRecord {

	private static function addMembers($conversations) {
		$result = array();
		foreach ($conversations as $conv) {
			$id = $conv["id"];
			$query =   "select part1.user, concat_ws(' ', part2.name, part2.surname) as name from
						(select user from conversation_user where conversation = $id) part1
						left join
						(select email, name, surname from teacher union all select email, name, surname from student) part2
						on part1.user = part2.email;";
			$r = Yii::$app->db->createCommand($query)->queryAll();

			$aux = array();
			foreach ($r as $m) {
				array_push($aux, $m["name"]);
			}
			$conv["members"] = $aux;
			array_push($result, $conv);
		}

		return $result;
	}

	public static function getConversations($email) {
		$query =   "select c.id, s.name as subject, c.name
					from conversation c, conversation_user cu, subject s
					where c.id = cu.conversation and c.subject = s.code
					and cu.user = '$email';";

		$conversations = Yii::$app->db->createCommand($query)->queryAll();

		return self::addMembers($conversations);
	}

	public static function checkConversation($conversation, $user) {
		$query =  "select count(*) from conversation_user where conversation = $conversation and user = '$user'";

		if(!Yii::$app->db->createCommand($query)->queryScalar()) {
			return false;
		}
		return true;
	}
}