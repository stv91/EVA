<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Exam extends ActiveRecord {

	public static function tableName() {
        return 'exam';
    }

    private static function getCurrentCourse() {
    	if(intval(date("n")) >= 9) {
    		return date("Y") . "-" . (intval(date("y")) + 1);
    	}
    	else {
    		return (intval(date("Y")) - 1)  . "-" . date("y");
    	}	
    }

    public static function getStudentExams($user, $degree) {
    	$course = self::getCurrentCourse();
    	$query =   "select ex.id, s.name as subject, DATE_FORMAT(ex.date, '%d/%m/%Y %H:%i') as start, DATE_FORMAT(ADDTIME(ex.date, ex.duration), '%d/%m/%Y %H:%i') as finish,
                    TIME_FORMAT(ex.duration, '%H:%i') as duration, ex.description, ex.student_questions as studentQuestions, ex.num_questions as numQuestions,
                    case when (NOW() between ex.date and ADDTIME(ex.date, ex.duration)) then 1 else 0 end as open
                    from subject s, degree_subject ds, tuition t, enrollment e, exam ex
                    where t.student = $user and t.degree like '$degree' and ds.degree = t.degree
                    and e.subject = ds.subject and ds.subject = s.code and ex.subject = s.code and e.course = '$course'
                    and (select count(*) from mark m where ex.id = m.exam and m.student = $user) = 0 order by ex.date asc;";

		$exams = Yii::$app->db->createCommand($query)->queryAll();
		return $exams;
    }

    public static function getTearcherExams($user, $degree) {
        $course = self::getCurrentCourse();
        $query =   "select distinct ex.id, s.name as subject, DATE_FORMAT(ex.date, '%d/%m/%Y %H:%i') as start, DATE_FORMAT(ADDTIME(ex.date, ex.duration), '%d/%m/%Y %H:%i') as finish,
                    TIME_FORMAT(ex.duration, '%H:%i') as duration, ex.description, ex.student_questions as studentQuestions, ex.num_questions as numQuestions
                    from exam ex, subject_course_teacher sct, degree_subject ds, subject s
                    where ds.subject = sct.subject and ex.subject = ds.subject and s.code = ds.subject 
                    and NOW() < ex.date
                    and ds.degree = '$degree' and sct.teacher = $user order by ex.date asc;";
        $exams = Yii::$app->db->createCommand($query)->queryAll();
        return $exams;
    }

    public static function checkExam($exam, $user, $isTeacher) {
    	$course = self::getCurrentCourse();

    	if(!$isTeacher) {
    		$query =   "select count(*)
						from subject s, enrollment e, exam ex
						where ex.subject = s.code and s.code = e.subject and e.student = $user and ex.student_questions = 1
						and e.course = '$course' and ex.id = $exam;";
    	}
    	else {
    		$query =   "select count(*)
						from exam ex, subject_course_teacher sct
						where ex.id = $exam and ex.subject = sct.subject and sct.teacher = $user;";
    	}

		if(!Yii::$app->db->createCommand($query)->queryScalar()) {
			return false;
		}
		return true;
    }

    public static function getExamInfo($id) {
        $query =   "select ex.id, s.name as subject, DATE_FORMAT(ex.date, '%d/%m/%Y %H:%i') as start, DATE_FORMAT(ADDTIME(ex.date, ex.duration), '%d/%m/%Y %H:%i') as finish,
                    TIME_FORMAT(ex.duration, '%H:%i') as duration, ex.description, ex.student_questions as studentQuestions
                    from subject s, exam ex where ex.subject = s.code and ex.id = $id;";

        $exam = Yii::$app->db->createCommand($query)->queryOne();
        return $exam;
    }
}