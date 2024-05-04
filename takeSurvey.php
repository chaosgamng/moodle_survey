<?php
// basic moode import 
require_once(__DIR__ . "/../../config.php");

// basic imports needed
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/QuestionDAO.php");

// basic moodle page setup
$PAGE->set_url(new moodle_url('/local/survey/takeSurvey.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('survey page');

// variables and objects created for later use
$data = $_GET['id'];
$con = new conn();
$d = new stdClass();

// getting all questions and then putting them in an array to be used in mustache file
$questions = new QuestionDAO($con);
$results = $questions->selectSurveyQuestion($data);
// makes sure there is something in results
if($results){
    // turing the results into a loopable array for mustache to interpret 
    $d->events = array_values($results);
    $d->survey = $data;
} 

// basic moodle page output
echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_survey/takeSurvey", $d);
echo $OUTPUT->footer();