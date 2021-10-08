<?php
/*
* REST web service for courses in JSON-format.
*
* Code by Sofie Wallin (sowa2002), student at MIUN, 2021.
*/

require_once('./resources/config.php');

/*------ Headers ------*/

header('Access-Control-Allow-Origin: *'); // Make web service accessible from all domains
header('content-type: application/json; charset=utf-8'); // Make sure data is sent in JSON format
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE'); // Allow methods GET, PUT, POST and DELETE
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With'); // Allow headers so that there is no problem with CORS (Cross-Origin Resource Sharing)

/*------ Database connection ------*/

$database = new Database(); // Get database
$database_connection = $database->getConnection(); // Get database connection

/*------ Course/courses class ------*/

$courseGateway = new CourseGateway($database_connection); // Create instance of class for course/courses and include database connection

/*------ Web service parameters ------*/

/* Check if ID is set as a parameter */
if(isset($_GET['id'])) {
    $id = $_GET['id']; // Set id
}

/*------ Web service methods and actions ------*/

$errors = [];
$errors['errors'] = [];

$method = $_SERVER['REQUEST_METHOD']; // Store requested method

/* Set actions based on requested method */
switch($method) {
    case 'GET':
        /* Check id ID is set */
        if(isset($id)) {
            $response = $courseGateway->getCourse($id); // Get course by id

            /* Check if no course with set id */
            if(sizeof($response) == 0) {
                http_response_code(404); // 404 Not Found
                array_push($errors['errors'], ["status" => "404", "message" => "Det finns ingen kurs med id $id."]);
                $response = $errors;
                break;
            }  
        } else {
            $response = $courseGateway->getCourses(); // Get all courses

            /* Check if any courses */
            if(sizeof($response) == 0) {
                http_response_code(404); // 404 Not Found
                array_push($errors['errors'], ["status" => "404", "message" => "Det finns inga kurser."]);
                $response = $errors;
                break;
            }
        }
        http_response_code(200); // 200 OK
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input')); // Read JSON-data from request as an object

        /* Check if start date is not valid and add error message */
        if(!$courseGateway->setStartDate($data->start_date)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i ett giltigt startdatum. Datumformatet ska vara 'YYYY-MM-DD'."]);
        }

        /* Check if end date is not valid and add error message */
        if(!$courseGateway->setEndDate($data->end_date)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i ett giltigt slutdatum. Datumformatet ska vara 'YYYY-MM-DD'."]);
        }

        /* Check if code is not valid and add error message */
        if(!$courseGateway->setCode($data->code)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i en giltig kurskod. Kurskoderna på Mittuniversitetet har max 7 tecken, de flesta är 6 tecken långa."]);
        }

        /* Check if title is not valid and add error message */
        if(!$courseGateway->setTitle($data->title)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i ett giltigt kursnamn, du behöver minst 2 tecken."]);
        }

        /* Check if progression is not valid and add error message */
        if(!$courseGateway->setProgression($data->progression)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i en giltig progression. En kandidatutbildning har progression från A-C."]);
        }

        /* Check if progression is not valid and add error message */
        if(!$courseGateway->setSyllabus($data->syllabus)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i en giltig kursplanslänk. Mittuniversitetets kursplaner har en länk som är formaterad enligt: https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=[id]. Fyll i korrekt id för den aktuella kursen."]);
        }

        /* Check if any errors */
        if($errors['errors']) {
            http_response_code(400); // 400 Bad Request
            $response = $errors;
            break;
        }

        // Check if course was successfully created
        if($courseGateway->createCourse()) {
            http_response_code(201); // 201 Created
            $response = array('message' => 'Kursen har skapats.');
        } else {
            http_response_code(503); // 503 Service Unavailable
            array_push($errors['errors'], ["status" => "503", "message" => "Kursen kunde inte skapas."]);
            $response = $errors;
        }
        break;
    case 'PUT':
        /* Check if id is not set */
        if(!isset($id)) {
            http_response_code(400); // 400 Bad Request
            array_push($errors['errors'], ["status" => "400", "message" => "Inget id har skickats."]);
            $response = $errors;
            break;
        }

        $id_match = false;
        $courses = $courseGateway->getCourses(); // Get courses

        /* Loop through courses */
        foreach($courses as $key => $value) {
            /* Check if the key id matches set id */
            if($courses[$key]['id'] == $id) {
                $id_match = true; // Course id match
                break;
            }
        }

        /* Check if set id didn't match any course id */
        if(!$id_match) {
            http_response_code(404); // 404 Not Found
            array_push($errors['errors'], ["status" => "404", "message" => "Det finns ingen kurs med id $id."]);
            $response = $errors;
            break;
        }

        $data = json_decode(file_get_contents('php://input')); // Read JSON-data from request as an object
        /* Check if start date is not valid and add error message */
        if(!$courseGateway->setStartDate($data->start_date)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i ett giltigt startdatum. Datumformatet ska vara 'YYYY-MM-DD'."]);
        }

        /* Check if end date is not valid and add error message */
        if(!$courseGateway->setEndDate($data->end_date)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i ett giltigt slutdatum. Datumformatet ska vara 'YYYY-MM-DD'."]);
        }

        /* Check if code is not valid and add error message */
        if(!$courseGateway->setCode($data->code)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i en giltig kurskod. Kurskoderna på Mittuniversitetet har max 7 tecken, de flesta är 6 tecken långa."]);
        }

        /* Check if title is not valid and add error message */
        if(!$courseGateway->setTitle($data->title)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i ett giltigt kursnamn, du behöver minst 2 tecken."]);
        }

        /* Check if progression is not valid and add error message */
        if(!$courseGateway->setProgression($data->progression)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i en giltig progression. En kandidatutbildning har progression från A-C."]);
        }

        /* Check if progression is not valid and add error message */
        if(!$courseGateway->setSyllabus($data->syllabus)) {
            array_push($errors['errors'], ["status" => "400", 'message' => "Du har inte fyllt i en giltig kursplanslänk. Mittuniversitetets kursplaner har en länk som är formaterad enligt: https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=[id]. Fyll i korrekt id för den aktuella kursen."]);
        }
    
        /* Check if any errors */
        if($errors['errors']) {
            http_response_code(400); // 400 Bad Request
            $response = $errors;
            break;
        }

        // Check if course was successfully updated
        if($courseGateway->editCourse($id)) {
            http_response_code(200); // 200 OK
            $response = array("message" => "Kursen med id $id har uppdaterats.");
        } else {
            http_response_code(503); // 503 Service Unavailable
            array_push($errors['errors'], ["status" => "503", "message" => "Kursen har med id $id kunde inte uppdateras."]);
            $response = $errors;
        }
        break;
    case 'DELETE':
        /* Check if id is not set */
        if(!isset($id)) {
            http_response_code(400); // 400 Bad Request
            array_push($errors['errors'], ["status" => "400", "message" => "Inget id har skickats."]);
            $response = $errors;
            break;
        }
        
        $id_match = false;
        $courses = $courseGateway->getCourses(); // Get courses

        /* Loop through courses */
        foreach($courses as $key => $value) {
            /* Check if the key id matches set id */
            if($courses[$key]['id'] == $id) {
                $id_match = true; // Course id match
                break;
            }
        }

        /* Check if set id didn't match any course id */
        if(!$id_match) {
            http_response_code(404); // 404 Not Found
            array_push($errors['errors'], ["status" => "404", "message" => "Det finns ingen kurs med id $id."]);
            $response = $errors;
            break;
        }

        /* Check if course was successfully deleted */
        if($courseGateway->deleteCourse($id)) {
            http_response_code(200); // 200 OK
            $response = array("message" => "Kursen med id $id har raderats.");
        } else {
            http_response_code(503); // 503 Service Unavailable
            array_push($errors['errors'], ["status" => "503", "message" => "Kursen med id $id kunde inte raderas."]);
            $response = $errors;
        }
        break;     
}

/*------ Web service response ------*/

/* Check if indent parameter is set */
if(isset($_GET['indent'])) {
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Show indented json
} else {
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Show non indented json
}