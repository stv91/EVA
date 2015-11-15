<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\Utils;

class Mark extends ActiveRecord {

	private static function formatMarksByStudent($marks) {
		$result = array();
		foreach ($marks as $mark) {
			$subject = $mark["subject"];
			$aux = array_slice($mark, 2);
			if(isset($result[$subject]["exams"])){
				array_push($result[$subject]["exams"], $aux);
			}
			else {
				$result[$subject] = array("exams" => array($aux));
			}
		}

		return $result;
	}

	public static function getMarksByStudent($id, $degree) {
		$course = Utils::getCurrentCourse();

		$query =   "select ex.subject as subjectID, s.name as subject, DATE_FORMAT(ex.date, '%d/%m/%Y') as date, m.value as mark
					from exam ex, enrollment en, mark m, degree_subject ds, subject s
					where en.subject = ex.subject and ex.id = m.exam and ds.subject = en.subject and en.subject = s.code
					and ds.degree = '$degree' and en.student = $id and en.course = '$course' order by ex.date asc;";

		$marks = Yii::$app->db->createCommand($query)->queryAll();
		return self::formatMarksByStudent($marks);
	}

	public static function getMarksByTeacher($id, $degree) {
		$course = Utils::getCurrentCourse();
	}
}