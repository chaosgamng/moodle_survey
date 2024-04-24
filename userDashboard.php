<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");

$PAGE->set_url(new moodle_url('/local/survey/userDashboard.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('survey dashboard');
$data = new stdClass();
$conn = new conn();
$conn = new conn();

$AssignDAO = new AssignDAO($conn);
$results = $AssignDAO->selectAssign($USER->id);
$surveyDAO = new SurveyDAO($conn);
$survey = $surveyDAO->getSurveysByCourse($COURSE->id);

foreach ($survey as $s) {
    if($results['survey_id'] != $s['survey_id']){
        unset($s);
    }
}

if($survey){
    $data->events = array_values($survey);
} 



echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_survey/userDashboard", $data);
echo $OUTPUT->footer();