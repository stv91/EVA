<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\Utils;

class DeadlineSubmit extends ActiveRecord {
	private $file;

	public function rules() {
        return [
            [['deadline', 'student'], 'required'],
            ['name', 'string', 'max' => 230],
        ];
    }

    private static $fileTypes = array (
        'application/pdf',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.presentation',
        'application/x-download'
    );

    private function checkFileType() {
    	foreach (self::$fileTypes as $type) {
            if($this->file['type'] == $type){
                return true;
            }
        }
        return false;
    }

    public function setData($deadline, $student, $file) {
    	$this->deadline = $deadline;
    	$this->student = $student;
    	$this->file = $file;

    	$timestamp = time();
    	$fileName = pathinfo($file['name'], PATHINFO_FILENAME);
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$this->name = $fileName . "-" . $timestamp . "." . $fileExtension;
		
    }

    public function addSubmit() {
    	if($this->validate() && $this->checkFileType()) {
    		//Empezamos la transacción
    		$transaction = Yii::$app->db->beginTransaction();
    		//Guardamos
    		$this->save();
    		//Movemos la imagen
    		$path = Yii::$app->basePath . '/web/deadlines/'. $this->deadline . '/' . $this->student . "/";
    		$uploaddir = Yii::$app->utils->stdPath($path);
            if(Yii::$app->utils->makeDirs($uploaddir)) {
                $uploadfile = $uploaddir . $this->name;

                if (move_uploaded_file($this->file['tmp_name'], $uploadfile)) { //Todo correcto
                	$transaction->commit(); //Finalizamos la transacción
                    return true;
                }
                else { //Error moviendo el archivo
                    error_log("Error moviendo el archivo");
                    $transaction->rollBack(); //Hacemos roll back
                    return false;
                }
            }
            else { //Error creando el archivo
                error_log("Error creando el archivo");
                $transaction->rollBack(); //Hacemos roll back
                return false;
            }
    	}
        error_log("formato incorrecto");
    	return false;
    }
}