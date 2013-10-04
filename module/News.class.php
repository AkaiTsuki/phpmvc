<?php

/**
 * Description of New
 *
 * @author jiachi
 */
class News {

    private $id;
    private $title;
    private $author;
    private $category;
    private $postDate;
    private $content;
    private $photo;
    private $introduction;

    public static function getInstance($title, $author, $category, $postDate, $content, $photo, $introduction) {
        $news = new News();

        $news->title = $title;
        $news->author = $author;
        $news->category = $category;
        $news->postDate = $postDate;
        $news->content = $content;
        $news->photo = $photo;
        $news->introduction=$introduction;
        
        return $news;
    }

    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    public function __get($name) {
        return $this->{$name};
    }

    /* Save the news to Database */
    public function save() {
        $sql = "insert into news(title,author,category,postDate,content,photoUrl,introduction) values (?,?,?,?,?,?,?)";
        $mysqli = ActiveObject::connect();
        $lastInsertId=0;
        
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param("ssissss",$title, $author,$category, $postDate,$content,$photo,$introduction);
            
            $title =$this->title;
            $author=$this->author;
            $category=$this->category;
            $postDate=$this->postDate;
            $content=$this->content;
            $photo=$this->photo;
            $introduction=  $this->introduction;
            
            $stmt->execute();
            $lastInsertId = $mysqli->insert_id;
            
            $stmt->close();
        }else{
            $error =$mysqli->error;
            ActiveObject::disconnect($mysqli);
            throw new Exception($error);
        }
        ActiveObject::disconnect($mysqli);
        return $lastInsertId;
    }
    
    public function update(){
        $sql = "UPDATE news SET title=?,author=?,category=?,postDate=?,content=?,photoUrl=?,introduction=? WHERE id=?";
        
        $affectedRows=0;
        $mysqli = ActiveObject::connect();
        
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param("ssissssi", $title,$author,$category,$postDate,$content,$photo,$introduction,$id);
            
            $title = $this->title;
            $author=  $this->author;
            $category = $this->category;
            $postDate = $this->postDate;
            $content = $this->content;
            $photo = $this->photo;
            $introduction = $this->introduction;
            $id = $this->id;
            
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
        
    }
    
    public static function delete($id){
        $affectedRows = 0;
        $sql = "DELETE FROM news WHERE id=" . $id;
        $mysqli = ActiveObject::connect();
        
        if($stmt=$mysqli->prepare($sql)){
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }
    
    /*
     * find all news in database. 
     * If lazyload = false, also find category name when query the news
     */
    public static function findAll($orderby="postDate",$lazyload=true){
        $sql = "select * from news order by ".$orderby." desc";
        
        if(!$lazyload){
            $sql = "select news.id,news.title,news.author,category.name,
                news.postDate,news.content,news.photoUrl,news.introduction 
                from news,category 
                where news.category=category.id
                order by news.".$orderby." desc";
        }
        
        $newsList = array();
        $mysqli = ActiveObject::connect();
        
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($id,$title,$author,$category,$postDate,$content,$photo,$introduction);

            while ($stmt->fetch()) {
                $news = News::getInstance($title, $author, $category, $postDate, $content, $photo,$introduction);
                $news -> id= $id;
                $newsList[]=$news;
            }
            
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $newsList;
    }

    public static function find($id) {
        $sql = "select * from news where id=?";
        $mysqli = ActiveObject::connect();
        $news=null;
        
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('i', $nid);
            $nid = $id;
            $stmt->execute();
            $stmt->bind_result($id,$title,$author,$category,$postDate,$content,$photo,$introduction);
            
            $stmt->fetch();
            
            $news = News::getInstance($title, $author, $category, $postDate, $content, $photo,$introduction);
            $news -> id = $id;
            $stmt->close();        
        }
        ActiveObject::disconnect($mysqli);
        return $news;
    }

}

?>
