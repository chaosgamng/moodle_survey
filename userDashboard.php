<?php
// basic moodle import
require_once(__DIR__ . "/../../config.php");

// all required imports
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/UserDAO.php");
require_once(__DIR__ . "/DAOS/CourseDAO.php");

// basic moodle page setup
$PAGE->set_url(new moodle_url('/local/survey/userDashboard.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('course dashboard');



// class for passing string data to mustache files
$data = new stdClass();
$conn = new conn();
$userDAO = new UserDAO($conn);
$AssignDAO = new AssignDAO($conn);
$results = $AssignDAO->selectAssign($USER->id);
$surveyDAO = new SurveyDAO($conn);
$survey = $surveyDAO->getSurveysByCourse($COURSE->id);



if(!($userDAO->getUser($USER->id))){
    $userDAO->insertUser($USER->id, $USER->firstname, $USER->lastname, $USER->email, '1234' ,'student', $USER->department, $USER->department);
}

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
            }
        } catch (Exception $e){
            echo "$e";
        }
    }

    // making sure survey is still there
    if($survey){
        // if so turn the array into a list we can use in a mustache file
        $data->events = array_values($survey);
    } 
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_survey/userDashboard", $data);
echo $OUTPUT->footer();