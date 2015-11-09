<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Material extends ActiveRecord {

	private $file;

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

	function setData($subject, $file) {

		$this->file = $file;
		$this->user = Yii::$app->user->identity->code;
		$this->is_teacher = Yii::$app->user->identity->isTeacher;
		$this->subject = $subject;
		$timestamp = time();
		$this->course = date("Y",$timestamp) . "-" . (intval(substr(date("Y",$timestamp), -2)) + 1);
		$this->original_name = Yii::$app->utils->normalize(basename($file['name']));
		
		$fileName = pathinfo($file['name'], PATHINFO_FILENAME);
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$this->local_name = $fileName . "-" . $timestamp . "." . $fileExtension;
        $this->local_name = Yii::$app->utils->normalize($this->local_name);
        $this->description = "<p> Sin descripción </p>";

        $this->type = $fileExtension;
  	}


	public function rules() {
        return [
            [['user', 'subject', 'course', 'original_name', 'type', 'local_name'], 'required'],
            ['original_name', 'string', 'max' => 230],
            ['local_name', 'string', 'max' => 255]
        ];
    }

    private function checkFileType() {
    	foreach (self::$fileTypes as $type) {
            if($this->file['type'] == $type){
                return true;
            }
        }
        return false;
    }

    public function addMaterial() {
    	if($this->validate() && $this->checkFileType()) {
    		//Empezamos la transacción
    		$transaction = Yii::$app->db->beginTransaction();
    		//Guardamos
    		$this->save();
    		//Movemos la imagen
    		$uploaddir = Yii::$app->utils->stdPath(Yii::$app->basePath . '/web/materials/'. $this->course . '/' . $this->subject . "/");
            if(Yii::$app->utils->makeDirs($uploaddir)) {
                error_log("local_name: $this->local_name");
                $uploadfile = $uploaddir . $this->local_name;

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

    public function deleteMaterial($user) {
        if($this->checkOwner($user)){
            //Empezamos la transacción
            $transaction = Yii::$app->db->beginTransaction();
            //Guardamos
            if($this->delete()) {
                $dir = Yii::$app->utils->stdPath(Yii::$app->basePath . '/web/materials/'. $this->course . '/' . $this->subject . "/");
                $file = $dir . $this->local_name;
                if(unlink($file)){
                    $transaction->commit(); //Finalizamos la transacción
                    return true;
                }
                else {
                    error_log("Error borrando el archivo");
                    $transaction->rollBack(); //Hacemos roll back
                    return false;
                }
            }
            else { //Error borrando de bd
                error_log("Error borrando de bd");
                $transaction->rollBack(); //Hacemos roll back
                return false;
            }
        }
        error_log("No es el propietario");
        return false;
    }

    public static function searchMaterial($text, $oficials, $noOficials, $course, $subject, $degree) {
        $query =    "select m.id, m.subject, m.original_name as name, DATE_FORMAT(m.timestamp, '%d/%m/%Y') as date, m.type
                     from material m, degree_subject ds where m.subject = ds.subject and ds.degree = '$degree' and 
                     (original_name like '%$text%' or description like '%$text%') and m.course = '$course'"; 
        if(!$oficials && !$noOficials){
            return array();
        }
        if(!($oficials && $noOficials)) {
            if($oficials){
                $query .= " and is_teacher = 1";
            }
            else {
                $query .= " and is_teacher = 0";
            }
        }
        $query .= ($subject == "-1")? "" : " and m.subject = '$subject'";

        $result = Yii::$app->db->createCommand($query)->queryAll();

        return $result;
    }

    public static function getMaterialByID($id) {
        $query = "select *, DATE_FORMAT(timestamp, '%d/%m/%Y') as date from material where id = $id";
        $result = Yii::$app->db->createCommand($query)->queryOne();

        return $result;
    }

    public function checkOwner($user) {
        return $user == $this->user;
    }
}
    