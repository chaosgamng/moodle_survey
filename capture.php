<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/ResponseDAO.php");

$PAGE->set_url(new moodle_url('/local/survey/capture.php'));
$PAGE->set_context(\context_system::instance());
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new conn();
    $responseDAO = new ResponseDAO($conn);
    $survey_id = $_GET['id'];
    $assignDAO = new AssignDAO($conn);
    $assignDAO->deleteAssign($survey_id, $USER->id);
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question') !== false) {
            $question_id = str_replace('question', '', $key);
            $responseDAO->insertResponse($question_id, $USER->id, $value, 0);

        } elseif (strpos($key, 'rating') !== false) {
            $question_id = str_replace('rating', '', $key);
            $responseDAO->insertResponse($question_id, $USER->id, $value, 1);
        }
    }

}
header("Location: /moodle/course/view.php?id=" . strval($COURSE->id));
?>