<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
 
class User implements \yii\web\IdentityInterface{
    
    public $code;
    public $name;
    public $surname;
    public $email;
    public $isTeacher;
    public $password;
    public $authKey;
    public $accessToken;
    public $degrees;
    
    public static function findIdentity($id)
    {
        return self::getUserById($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $query = new Query;
        $query->select("*")
            ->from("user")
            ->where("accessToken = '$token'");
        $result = $query->one();
        if(is_array($result)){
            return self::getUserById($result["email"]);
        }

        return null;
    }
    
    public function getId()
    {
        return $this->email;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    private static function genUser($array, $isTeacher){
        $user = new User;
        $user->code = $array["code"];
        $user->name = $array["name"];
        $user->surname = $array["surname"];
        $user->email = $array["email"];
        $user->isTeacher = $isTeacher;
        return $user;
    }
    
    public static function getUserById($email) 
    {
        $user = null; 
        
        $query = new Query;
        $query->select("*")
            ->from("teacher")
            ->where("email = '$email'");
        $result = $query->one();
        if(is_array($result))
            $user = self::genUser($result, true);
        else {
            $query->select("*")
                ->from("student")
                ->where("email = '$email'");
            $result = $query->one();
            if(is_array($result))
                $user = self::genUser($result, false);
        }
        
        if($user != null){
            $query->select("*")
                ->from("user")
                ->where("email = '$email'");
            $result = $query->one();
            if(is_array($result)){
                $user->password = $result["password"];
                $user->authKey = $result["authKey"];
                $user->accessToken = $result["accessToken"];
            }

            $sql = "select degree from tuition where student = $user->code";
            if($user->isTeacher == 1){
                $sql =    "select distinct ds.degree from subject_course_teacher sct, degree_subject ds
                             where ds.subject = sct.subject and teacher = $user->code;";
            }

            $result = Yii::$app->db->createCommand($sql)->queryAll();
            
            if(is_array($result)){
                for ($i = 0; $i < count($result); $i++) {
                    $query->select("name")
                        ->from("degree")
                        ->where("code = '".$result[$i]["degree"]."'");
                    $aux = $query->one();
                    $result[$i]["name"] = $aux["name"];
                }
                $user->degrees = $result;
                if(!Yii::$app->session["currentDegree"]){
                    Yii::$app->session["currentDegree"] =  $result[0]["degree"];    
                }
                
            }
        }
        
        return $user;
    }
    
    public function validatePassword($pass){
        return ($this->password == md5($pass));
    }

    public function checkSubject($subject) {
        $query =    "select * from enrollment where student = '$this->code' and subject = '$subject'";
        if($this->isTeacher == 1) {
            $query = "select * from subject_course_teacher where teacher = $this->code and subject = '$subject';";
        }
        $result = Yii::$app->db->createCommand($query)->queryAll();

        return count($result) > 0;
    }
}

