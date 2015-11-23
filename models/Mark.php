<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\Utils;

class Mark extends ActiveRecord {

	private static function formatMarksByStudent($examMarks, $deadlineMarks) {
		$result = array();
		foreach ($examMarks as $mark) {
			$subject = $mark["subject"];
			$aux = array_slice($mark, 2);
			if(isset($result[$subject]["exams"])){
				array_push($result[$subject]["exams"], $aux);
			}
			else {
				$result[$subject] = array("exams" => array($aux));
			}
		}

		foreach ($deadlineMarks as $mark) {
			$subject = $mark["subject"];
			$aux = array_slice($mark, 2);
			if(isset($result[$subject]["deadlines"])){
				array_push($result[$subject]["deadlines"], $aux);
			}
			else {
				if(isset($result[$subject])){
					$result[$subject]["deadlines"] = array($aux);
				}
				else {
					$result[$subject] = array("deadlines" => array($aux));
				}
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

		$examMarks = Yii::$app->db->createCommand($query)->queryAll();

		$query =   "select d.subject as subjectID, s.name as subject, d.name, DATE_FORMAT(d.date, '%d/%m/%Y') as date, m.value as mark
					from deadline d, enrollment en, mark m, degree_subject ds, subject s
					where en.subject = d.subject and d.id = m.work and ds.subject = en.subject and en.subject = s.code
					and ds.degree = '$degree' and en.student = $id and en.course = '$course' order by d.date asc;";

		$deadlineMarks = Yii::$app->db->createCommand($query)->queryAll();

		return self::formatMarksByStudent($examMarks, $deadlineMarks);
	}

	public static function getExamsDone($id, $degree) {
		$course = Utils::getCurrentCourse();

		$query =   "select distinct ex.id,  s.name as subject, DATE_FORMAT(ex.date, '%d/%m/%Y') as date
					from exam ex, subject s, degree_subject ds, subject_course_teacher sct
					where ex.subject = s.code and ds.subject = s.code and sct.subject = s.code
					and ex.date < NOW() and ds.degree = '$degree' and sct.teacher = '$id';";

		return Yii::$app->db->createCommand($query)->queryAll();
	}

	public static function getDeadlineFinished($id, $degree) {
		$course = Utils::getCurrentCourse();

		$query =   "select distinct d.id,  s.name as subject, d.name, DATE_FORMAT(d.date, '%d/%m/%Y') as date
					from deadline d, subject s, degree_subject ds, subject_course_teacher sct
					where d.subject = s.code and ds.subject = s.code and sct.subject = s.code
					and d.date < NOW() and ds.degree = '$degree' and sct.teacher = '$id';";

		return Yii::$app->db->createCommand($query)->queryAll();
	}

	public static function getAllExamMarks($id) {
		$query =   "select m.student, concat_ws(' ', s.name, s.surname) as name, m.value as mark
					from student s, mark m where s.code = m.student and m.exam = $id;";

		return Yii::$app->db->createCommand($query)->queryAll();
	}

	public static function getAllDeadlineMarks($id) {
		$query =   "select part1.*, part2.file, part2.path from 
					(select m.student, concat_ws(' ', s.name, s.surname) as name, m.value as mark
					from student s, mark m where s.code = m.student and m.work = $id) as part1
					left join
					(select dsub.student, dsub.name as file, concat_ws('/', dsub.deadline, dsub.student, dsub.name)  as path
					from deadline d, deadline_submit dsub
					where dsub.deadline = d.id and d.id = $id and
					dsub.date in (select max(date) from deadline_submit where student = dsub.student)) as part2
					on part1.student = part2.student;";

		error_log($query);

		return Yii::$app->db->createCommand($query)->queryAll();
	}
}