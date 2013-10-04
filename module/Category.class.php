<?php

include_once 'ActiveObject.class.php';

/**
 * Description of Category
 *
 * @author jiachi
 */
class Category extends ActiveObject {

    public static function findAll() {
        $sql = "SELECT * FROM category";
        $categories = array();

        $mysqli = ActiveObject::connect();

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($id, $name);

            while ($stmt->fetch()) {
                $categories[] = Category::getInstance($id, $name);
            }
            
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $categories;
    }
    
    public static function find($id){
        $sql = "SELECT * FROM category where id=?";
        $mysqli = ActiveObject::connect();
        $category = null;
        
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('i', $cid);
            $cid =$id;
            
            $stmt->execute();
            $stmt->bind_result($id, $name);
            $stmt->fetch();
            $category = Category::getInstance($id, $name);
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $category;
    }
    
    public static function update($id,$name){
        $sql="UPDATE category SET name=? WHERE id=?";
        $affectedRows=0;
        $mysqli = ActiveObject::connect();
        
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param("si", $cname,$cid);
            $cname = $name;
            $cid = $id;
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }
    
    public static function delete($id){
        $affectedRows = 0;
        $sql = "DELETE FROM category WHERE id=" . $id;
        $mysqli = ActiveObject::connect();
        
        if($stmt=$mysqli->prepare($sql)){
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }
    
    public static function create($name){
        $affectedRows = 0;
        $sql="INSERT INTO category(name) VALUES(?)";
        $mysqli = ActiveObject::connect();
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('s',$cname);
            $cname=$name;
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }

    public static function getInstance($id, $name) {
        $c = new Category();
        $c->id = $id;
        $c->name = $name;
        return $c;
    }

    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    public function __get($name) {
        return $this->{$name};
    }

}

?>
