<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\Utils;

class Deadline extends ActiveRecord {

	public static function getDeadlinesByStudent($user, $degree) {
		$course = Utils::getCurrentCourse();
        $query =   "select allData.*, submit.file, submit.path from
					(select d.id, d.subject, d.name, DATE_FORMAT(d.date, '%d/%m/%Y') as date, d.description, s.name as subjectName
					from deadline d, enrollment e, subject s, degree_subject ds
					where d.subject = e.subject and e.subject = s.code and s.code = ds.subject
					and NOW() < d.date and e.course = '$course' and ds.degree = '$degree' and e.student = $user) as allData
					left join
					((select d.id, dsub.name as file, concat_ws('/', dsub.deadline, dsub.student, dsub.name)  as path 
					from deadline d, deadline_submit dsub
					where dsub.deadline = d.id and
					dsub.date in (select max(date) from deadline_submit where deadline = d.id and student = $user))) as submit
					on allData.id = submit.id;";


        $deadlines = Yii::$app->db->createCommand($query)->queryAll();
        return $deadlines;
	}

	public static function getDeadlinesByTeacher($user, $degree) {
        $query =   "select distinct d.id, d.subject, d.name, DATE_FORMAT(d.date, '%d/%m/%Y') as date, d.description, s.name as subjectName
					from deadline d, subject s, subject_course_teacher sct, degree_subject ds
					where ds.subject = sct.subject and ds.subject = s.code and ds.subject = d.subject
					and ds.degree = '$degree' and sct.teacher = $user;";


        $deadlines = Yii::$app->db->createCommand($query)->queryAll();
        return $deadlines;
	}

	public static function checkDeadline($id, $user, $degree, $isTeacher) {
		$course = Utils::getCurrentCourse();
		$query =   "select count(*)
					from deadline d, degree_subject ds, enrollment e
					where d.subject = ds.subject and e.subject = d.subject
					and ds.degree = '$degree' and d.id = $id and  e.student = $user and e.course = '$course';";

		if($isTeacher == 1) {
			$query =   "select count(*)
						from deadline d, degree_subject ds, subject_course_teacher sct
						where d.subject = ds.subject and sct.subject = d.subject
						and ds.degree = '$degree' and d.id = $id and sct.teacher = $user;";
		}

        if(!Yii::$app->db->createCommand($query)->queryScalar()) {
			return false;
		}
		return true;
	}

}