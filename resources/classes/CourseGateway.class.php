<?php
/*
* Code by Sofie Wallin (sowa2002), student at MIUN, 2021
*/

class CourseGateway {

    /*------ Properties ------*/

    private $database_connection = NULL;
    private $database_table = 'courses';

    private $start_date;
    private $end_date;
    private $code;
    private $title;
    private $progression;
    private $syllabus_url;

    /*------ Methods ------*/

    // Constructor: Connect to database
    function __construct($database_connection) { 
        $this->database_connection = $database_connection;
    }

    // Set course start date
    public function setStartDate(string $start_date) : bool {
        if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$start_date)) {
            $this->start_date = $start_date;
            return true;
        } else {
            return false;
        }
    }

    // Set course end date
    public function setEndDate(string $end_date) : bool {
        if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$end_date)) {
            $this->end_date = $end_date;
            return true;
        } else {
            return false;
        }
    }

    // Set course code
    public function setCode(string $code) : bool {
        $code = strip_tags($code);
        $code = htmlspecialchars($code, ENT_QUOTES);
        if(strlen($code) > 0 && strlen($code) <= 7) {
            $this->code = $code;
            return true;
        } else {
            return false;
        }
    }

    // Set course title
    public function setTitle(string $title) : bool {
        $title = strip_tags($title);
        $title = htmlspecialchars($title, ENT_QUOTES);
        if(strlen($title) >= 2) {
            $this->title = $title;
            return true;
        } else {
            return false;
        }
    }

    // Set course progression
    public function setProgression(string $progression) : bool {
        $progression = strip_tags($progression);
        $progression = htmlspecialchars($progression, ENT_QUOTES);
        if($progression === 'A' || $progression === 'B' || $progression === 'C') {
            $this->progression = $progression;
            return true;
        } else {
            return false;
        }
    }

    // Set course syllabus
    public function setSyllabus(string $syllabus_url) : bool {
        $syllabus_url = strip_tags($syllabus_url);
        $syllabus_url = filter_var($syllabus_url, FILTER_SANITIZE_URL);
        if(filter_var($syllabus_url, FILTER_VALIDATE_URL)) {
            $this->syllabus_url = $syllabus_url;
            return true;
        } else {
            return false;
        }
    }

    // Create course
    public function createCourse() : bool {
        if(!isset($this->start_date)) { return false; }
        if(!isset($this->end_date)) { return false; }
        if(!isset($this->code)) { return false; }
        if(!isset($this->title)) { return false; }
        if(!isset($this->progression)) { return false; }
        if(!isset($this->syllabus_url)) { return false; }
        // Prepare statement for database query
        $statement = $this->database_connection->prepare("INSERT INTO $this->database_table(start_date, end_date, code, title, progression, syllabus) VALUES(?, ?, ?, ?, ?, ?);");
        // Bind parameters to statement
        $statement->bind_param('ssssss', $this->start_date, $this->end_date, $this->code, $this->title, $this->progression, $this->syllabus_url);
        // Execute statement
        $result = $statement->execute();
        // Close statement
        $statement->close();
        // Return result
        return $result;
    }

    // Get list of courses
    function getCourses() : array {
        // Prepare statement for database query
        $statement = $this->database_connection->prepare("SELECT course_id AS id, start_date, end_date, code, title, progression, syllabus FROM $this->database_table ORDER BY start_date ASC");
        // Execute statement and get result
        $statement->execute();
        $result = $statement->get_result();
        // Get array of posts
        $courses = $result->fetch_all(MYSQLI_ASSOC);
        // Close statement
        $statement->close(); 
        // Return array of posts
        return $courses;
    }

    // Get course
    public function getCourse(int $course_id) : array {
        $course_id = intval($course_id);
        // Prepare statement for database query
        $statement = $this->database_connection->prepare("SELECT course_id AS id, start_date, end_date, code, title, progression, syllabus FROM $this->database_table WHERE course_id=?");
        // Bind parameters to statement
        $statement->bind_param('i', $course_id);
        // Execute statement and get result
        $statement->execute();
        $result = $statement->get_result();
        // Get array of posts
        $course = $result->fetch_all(MYSQLI_ASSOC);
         // Close statement
        $statement->close(); 
        // Return array of posts
        return $course;
    }

    // Edit course 
    public function editCourse(int $course_id) : bool {
        if(!isset($this->start_date)) { return false; }
        if(!isset($this->end_date)) { return false; }
        if(!isset($this->code)) { return false; }
        if(!isset($this->title)) { return false; }
        if(!isset($this->progression)) { return false; }
        if(!isset($this->syllabus_url)) { return false; }
        $course_id = intval($course_id);
        // Prepare statement for database query
        $statement = $this->database_connection->prepare("UPDATE $this->database_table SET start_date=?, end_date=?, code=?, title=?, progression=?, syllabus=? WHERE course_id=?;");
        // Bind parameters to statement
        $statement->bind_param('ssssssi', $this->start_date, $this->end_date, $this->code, $this->title, $this->progression, $this->syllabus_url, $course_id);
        // Execute statement
        $result = $statement->execute();
        // Close statement
        $statement->close();
        // Return result
        return $result;
    }

    // Delete course
    public function deleteCourse(int $course_id) : bool {
        $course_id = intval($course_id);
        // Prepare statement for database query
        $statement = $this->database_connection->prepare("DELETE FROM $this->database_table WHERE course_id=?;");
        // Bind parameters to statement
        $statement->bind_param('i', $course_id);
        // Execute statement
        $result = $statement->execute();
        // Close statement
        $statement->close();
        // Return result
        return $result;
    }

    

}