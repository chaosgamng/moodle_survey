<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/QuestionDAO.php");

$PAGE->set_url(new moodle_url('/local/survey/takeSurvey.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('survey page');
$data = $_GET['id'];
$con = new conn();
$d = new stdClass();

$questions = new QuestionDAO($con);
$results = $questions->selectSurveyQuestion($data);
if($results){
    $d->events = array_values($results);
    $d->survey = $data;
} 

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_survey/takeSurvey", $d);
echo $OUTPUT->footer();