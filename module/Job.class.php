<?php

include_once 'ActiveObject.class.php';

/**
 * Description of Job
 *
 * @author jiachi
 */
class Job extends ActiveObject {

    const REQUIREMENT = 0;
    const QUALIFICATION = 1;
    const DESIRED_QUALIFICATION = 3;
    const COMPENSATION = 4;

    private $id;
    private $title;
    private $department;
    private $description;
    private $requirements = array();
    private $qualifications = array();
    private $desiredQualifications = array();
    private $compensation = array();

    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    public function __get($name) {
        return $this->{$name};
    }

    public static function getInstance($title, $department, $description, $requirements, $qualifications, $desire, $compensation) {
        $job = new Job();
        $job->title = $title;
        $job->department = $department;
        $job->description = $description;
        $job->requirements = $requirements;
        $job->qualifications = $qualifications;
        $job->desiredQualifications = $desire;
        $job->compensation = $compensation;
        return $job;
    }

    public static function find($id) {
        $sql = "select * from job where id=?";
        $mysqli = ActiveObject::connect();
        $job = new Job();

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $jid);
            $jid = $id;
            $stmt->execute();
            $stmt->bind_result($id, $title, $department, $description);
            $stmt->fetch();
            $job->id = $id;
            $job->title = $title;
            $job->department = $department;
            $job->description = $description;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $job;
    }

    public static function delete($jobs) {
        $sql = "Delete from job where id=?";

        $mysqli = ActiveObject::connect();
        $affectedRows = 0;

        if ($stmt = $mysqli->prepare($sql)) {
            foreach ($jobs as $job) {
                $stmt->bind_param("i", $id);
                $id = $job;
                $stmt->execute();
                $affectedRows+=$mysqli->affected_rows;
            }
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }

    public static function findRequirement($id) {
        $sql = "select * from jobrequirements where id=?";
        $result = array();
        $mysqli = ActiveObject::connect();

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $rid);
            $rid = $id;
            $stmt->execute();
            $stmt->bind_result($id, $content, $type, $jobId);
            $stmt->fetch();
            $result = array("id" => $id, "content" => $content, "type" => $type, "jobId" => $jobId);
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $result;
    }

    public static function deleteRequirement($id) {
        $sql = "delete from jobrequirements where id=?";
        $affectedRows = 0;
        $mysqli = ActiveObject::connect();

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $rid);
            $rid = $id;

            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }
    
    public static function saveRequirement($content,$type,$jobId){
        $sql = "insert into jobrequirements(content,type,jobId) values (?,?,?)";
        $mysqli = ActiveObject::connect();
        $lastInsertId = 0;
        
        if ($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sii",$c,$t,$jid);
            $c=$content;
            $t=$type;
            $jid=$jobId;
            $stmt->execute();
            $lastInsertId = $mysqli->insert_id;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $lastInsertId;
    }

    public static function updateRequirement($id, $content) {
        $sql = "UPDATE jobrequirements SET content=? WHERE id=?";
        $affectedRows = 0;
        $mysqli = ActiveObject::connect();

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $c, $rid);
            $c = $content;
            $rid = $id;

            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }

    public function update() {
        $sql = "UPDATE job SET title=?, department=?, description=? where id=?";
        $affectedRows = 0;
        $mysqli = ActiveObject::connect();

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssi", $title, $depart, $desc, $id);
            $title = $this->title;
            $depart = $this->department;
            $desc = $this->description;
            $id = $this->id;

            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }

        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }

    public function loadRequirements() {
        $list = $this->readRequirementsFromDB();
        $requirements = array();
        $qualification = array();
        $desire = array();
        $compensation = array();

        foreach ($list as $item) {
            switch ($item['type']) {
                case Job::REQUIREMENT:
                    $requirements[] = $item;
                    break;
                case Job::QUALIFICATION:
                    $qualification[] = $item;
                    break;
                case Job::DESIRED_QUALIFICATION:
                    $desire[] = $item;
                    break;
                case Job::COMPENSATION:
                    $compensation[] = $item;
                    break;
            }
        }
        $this->requirements = $requirements;
        $this->qualifications = $qualification;
        $this->desiredQualifications = $desire;
        $this->compensation = $compensation;
    }

    public function readRequirementsFromDB() {
        $sql = "select * from jobrequirements where jobId=" . $this->id;
        $mysqli = ActiveObject::connect();
        $list = array();
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($id, $content, $type, $jobId);
            while ($stmt->fetch()) {
                $item = array("id" => $id, "content" => $content, "type" => $type, "jobId" => $jobId);
                $list[] = $item;
            }
            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $list;
    }

    public function save() {
        $jobId = $this->saveJob();
        return $this->saveJobRequirements($jobId);
    }

    public function saveJobRequirements($jobId) {
        $sql = "insert into jobrequirements(content,type,jobId) values(?,?,?)";
        $mysqli = ActiveObject::connect();
        $affectedRows = 0;

        if ($stmt = $mysqli->prepare($sql)) {
            foreach ($this->requirements as $item) {
                $stmt->bind_param("sss", $content, $type, $job);
                $content = $item;
                $type = Job::REQUIREMENT;
                $job = $jobId;
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
            }
            foreach ($this->qualifications as $item) {
                $stmt->bind_param("sss", $content, $type, $job);
                $content = $item;
                $type = Job::QUALIFICATION;
                $job = $jobId;
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
            }
            foreach ($this->desiredQualifications as $item) {
                $stmt->bind_param("sss", $content, $type, $job);
                $content = $item;
                $type = Job::DESIRED_QUALIFICATION;
                $job = $jobId;
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
            }
            foreach ($this->compensation as $item) {
                $stmt->bind_param("sss", $content, $type, $job);
                $content = $item;
                $type = Job::COMPENSATION;
                $job = $jobId;
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
            }
            $stmt->close();
        } else {
            $error = $mysqli->error;
            ActiveObject::disconnect($mysqli);
            throw new Exception($error);
        }
        ActiveObject::disconnect($mysqli);
        return $affectedRows;
    }

    public static function findAll() {
        $sql = "select * from job";
        $jobs = array();
        $mysqli = ActiveObject::connect();
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($id, $title, $department, $description);

            while ($stmt->fetch()) {
                $job = new Job();
                $job->id = $id;
                $job->title = $title;
                $job->department = $department;
                $job->description = str_replace("\n", "<br>", $description);
                $jobs[] = $job;
            }

            $stmt->close();
        }
        ActiveObject::disconnect($mysqli);
        return $jobs;
    }

    public function saveJob() {
        $sql = "insert into job(title,department,description) values (?,?,?)";
        $mysqli = ActiveObject::connect();
        $lastInsertId = -1;

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sss", $title, $depart, $desc);
            $title = $this->title;
            $depart = $this->department;
            $desc = $this->description;
            $stmt->execute();
            $lastInsertId = $mysqli->insert_id;
            $stmt->close();
        } else {
            $error = $mysqli->error;
            ActiveObject::disconnect($mysqli);
            throw new Exception($error);
        }
        ActiveObject::disconnect($mysqli);
        return $lastInsertId;
    }

    public function toString() {
        print_r($this);
    }

}

?>
