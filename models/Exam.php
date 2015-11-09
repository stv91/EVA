<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Exam extends ActiveRecord {

	public static function tableName() {
        return 'exam';
    }

    public static function getStudentExams($user, $degree) {
    	$query =   "select ex.id, s.name as subject, DATE_FORMAT(ex.date, '%d/%m/%Y %H:%i') as start, DATE_FORMAT(ADDTIME(ex.date, ex.duration), '%d/%m/%Y %H:%i') as finish,
					TIME_FORMAT(ex.duration, '%H:%i') as duration, ex.description
					from subject s, degree_subject ds, tuition t, enrollment e, exam ex 
					where t.student = $user and t.degree like '$degree' and ds.degree = t.degree 
					and e.subject = ds.subject and ds.subject = s.code and ex.subject = s.code;";

		$exams = Yii::$app->db->createCommand($query)->queryAll();
		return $exams;
    }
}