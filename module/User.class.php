<?php
include_once 'ActiveObject.class.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author jiachi
 */
class User {
    
    private $id;
    private $username;
    private $password;
    
    public static function find($username,$password){
        $mysqli = ActiveObject::connect();
        $result = null;
        $sql = "SELECT * FROM accounts WHERE username=? AND password=?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ss', $user,$pass);
            $user=$username;
            $pass=$password;
            
            $stmt->execute();
            $stmt->bind_result($rid, $rusername, $rpassword);

            $result = $stmt->fetch();
            
            if($result){
                $result = User::getInstance($rid, $rusername, $rpassword);
            }
            
            $stmt->close();
        }
        
        ActiveObject::disconnect($mysqli);
        return $result;
    }
    
    public function changePassword(){
        $mysqli = ActiveObject::connect();
        $sql = "Update accounts Set password=? Where id=?";
        $affectedRows = 0;
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('si', $pass,$id);
            
            $pass = $this->password;
            $id = $this->id;
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
         ActiveObject::disconnect($mysqli);
         return $affectedRows;
    }
    
    public static function getInstance($id,$username,$password){
        $user = new User();
        $user->id=$id;
        $user->username=$username;
        $user->password=$password;
        return $user;
    }
    
    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    public function __get($name) {
        return $this->{$name};
    }
    
    
}

?>
