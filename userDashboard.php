<?php
// basic moodle import
require_once(__DIR__ . "/../../config.php");

// all required imports
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/UserDAO.php");
require_once(__DIR__ . "/DAOS/CourseDAO.php");
require_once(__DIR__ . "/DAOS/moodleConn.php");
require_once(__DIR__ . "/DAOS/logs.php");

// basic moodle page setup
$PAGE->set_url(new moodle_url('/local/survey/userDashboard.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('course dashboard');



// class for passing string data to mustache files
$conn = new conn();
$userDAO = new UserDAO($conn);
$AssignDAO = new AssignDAO($conn);
$CourseDAO = new CourseDAO($conn);
$results = $AssignDAO->selectAssign($USER->id);
$surveyDAO = new SurveyDAO($conn);
$survey = $surveyDAO->getSurveysByCourse($COURSE->id);
$moodleconn = new moodleConn();
$logDAO = new logs($moodleconn);


// this checks to see if the user is in the database this way we can actually just make users in moodle and they will be enrolled in our database as well
if(!($userDAO->getUser($USER->id))){
    $password = password_hash("1234", PASSWORD_DEFAULT);
    $userDAO->insertUser($USER->id, $USER->firstname, $USER->lastname, $USER->email, $password ,'student', $USER->department, $USER->department);
} else {
    // we are going to see if all of the information in our database is the same as moodle if not update moodle database
    $d = $userDAO->getUser($USER->id);
    // checks to see if first name is same if not then we are going to update all information in database from moodle
    if(!($d['first_name'] === $USER->firstname)){
        $password = password_hash("1234", PASSWORD_DEFAULT);
        $userDAO->updateUser($USER->id, $USER->firstname, $USER->lastname, $USER->email, $password, "student", $USER->department, $USER->department);
    }
}

// we meed to see if the course we are in is in our database
$co = $CourseDAO->getCourse($COURSE->id);
if(!$co){
    // if no course in our database we need to add it
    $course = $logDAO->getlog($COURSE->id);
    
    if($course){
        if($userDAO->getUser($USER->id)['role'] === "professor"){
            $CourseDAO->insertCourse($course['other']['fullname'], $course['other']['shortname'], $course['userid']);
        } else {
            $error = "person who created course is not a professor in database";
        }
    }
}

$data = new stdClass();
// checking to see if our user is assigned anything
if($results){
    foreach ($survey as $s) {
        // making sure that we don't get an error while looping through
        try {
            // making sure survey id is in the survey row
            if(isset($s['survey_id'])){
                // checking to see if our id is in our results
                if(!in_array($s['survey_id'], $results) ){
                    // if not getting rid of row in surveys
                    unset($s);
                }
                // checks to see if the survey is active if not then don't show it
                if(!($s['status'] === "Active" )){
                    // if not getting rid of row in surveys
                    unset($s);
                }
            }
        } catch (Exception $e){
            // just prints error
            echo "$e";
        }
    }

    // making sure survey is still there
    if($survey){
        // if so turn the array into a list we can use in a mustache file
        $data->events = array_values($survey);
    } 
}

// basic moodle page display
echo $OUTPUT->header();
var_dump($COURSE->id);
echo $OUTPUT->render_from_template("local_survey/userDashboard", $data);
echo $OUTPUT->footer();