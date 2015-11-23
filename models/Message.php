<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\Utils;

class Message extends ActiveRecord {
	public static function getMessages($conversation) {
		$query =   "select part1.*, part2.user from
					(select m.id, m.user as email, DATE_FORMAT(m.date, '%d/%m/%Y %H:%i') as date, m.text
					from message m where m.conversation = $conversation order by m.date desc) as part1
					left join
					((select email, concat_ws(' ', name, surname) as user from student)
					union all
					(select email, concat_ws(' ', name, surname) as user from teacher)) as part2
					on part1.email = part2.email;";

		return Yii::$app->db->createCommand($query)->queryAll();
	}
}