<?php
// basic moodle import
require_once(__DIR__ . "/../../config.php");

// basic imports needed
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/ResponseDAO.php");

// basic moodle setup
$PAGE->set_url(new moodle_url('/local/survey/capture.php'));
$PAGE->set_context(\context_system::instance());

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // basic objects and variables created for further use
    $conn = new conn();
    $responseDAO = new ResponseDAO($conn);
    $survey_id = $_GET['id'];
    $assignDAO = new AssignDAO($conn);

    // making sure the user can not take the survey twice
    $assignDAO->deleteAssign($survey_id, $USER->id);
    
    // getting the information from post to put into our response table
    foreach ($_POST as $key => $value) {
        // grabs text questions
        if (strpos($key, 'question') !== false) {

            // grabs the id of the text question
            $question_id = str_replace('question', '', $key);
            // inserts response 
            $responseDAO->insertResponse($question_id, $USER->id, $value, 0);

            // for rating (1-10) responses
        } elseif (strpos($key, 'rating') !== false) {
            // grabs the id of the rating response
            $question_id = str_replace('rating', '', $key);
            // inserts response
            $responseDAO->insertResponse($question_id, $USER->id, $value, 1);
        }
    }

}
// points back to course
header("Location: /moodle/course/view.php?id=" . strval($COURSE->id));
?>