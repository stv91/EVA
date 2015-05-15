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
        }
        
        return $user;
    }
    
    public function validatePassword($pass){
        return ($this->password == md5($pass));
    }
     
}

