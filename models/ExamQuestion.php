<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ExamQuestion extends ActiveRecord {

	public function rules() {
        return [
            [['exam', 'user', 'question', 'correct_answer', 'answer1'], 'required'],
            ['question', 'string', 'max' => 512],
            ['correct_answer', 'string', 'max' => 512],
            ['answer1', 'string', 'max' => 512],
            ['answer2', 'string', 'max' => 512],
            ['answer3', 'string', 'max' => 512],
        ];
    }

}