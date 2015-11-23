<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use app\components\Utils;

class Subject extends ActiveRecord {

	public static function tableName() {
        return 'subject';
    }

	public static function getSubjectsByUser($degree, $user, $isTeacher) {
		$query = 	"select s.*, ds.course from subject s, degree_subject ds, tuition t, enrollment e
				where t.student = $user and t.degree like '$degree' and ds.degree = t.degree and e.subject = ds.subject
				and ds.subject = s.code;";

		if($isTeacher == 1) {
			$query = 	"select distinct s.*, ds.course from subject s, subject_course_teacher sct, degree_subject ds
						where ds.degree = '$degree' and sct.teacher = $user and ds.subject = sct.subject and ds.subject = s.code;";
		}

		$result = Yii::$app->db->createCommand($query)->queryAll();

		return $result;
	}

	public static function getSubjectByCode($code) {
		$query = 	"select * from subject where code = '$code'";
		$result = Yii::$app->db->createCommand($query)->queryOne();

		return $result;
	}

	public static function checkSubject($subject, $user, $isTeacher){
		$course = Utils::getCurrentCourse();
		$query = "select count(*) from enrollment where student = '$user' and course = '$course' and subject = '$subject'";
		if($isTeacher == 1){
			$query = "select count(*) from subject_course_teacher where teacher = '$user' and subject = '$subject';";
		}
		if(!Yii::$app->db->createCommand($query)->queryScalar()) {
			return false;
		}
		return true;
	}
}