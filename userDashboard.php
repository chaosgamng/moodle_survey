<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/UserDAO.php");

$PAGE->set_url(new moodle_url('/local/survey/userDashboard.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('survey dashboard');
$data = new stdClass();
$conn = new conn();
$users = new UserDAO($conn);
if(!($users->getUser($USER->id))){
    $users->insertUser($USER->id, $USER->firstname, $USER->lastname, $USER->email, '1234' ,'student', $USER->department, $USER->department);
}
$AssignDAO = new AssignDAO($conn);
$results = $AssignDAO->selectAssign($USER->id);
$surveyDAO = new SurveyDAO($conn);
$survey = $surveyDAO->getSurveysByCourse($COURSE->id);

foreach ($survey as $s) {
    if(isset($results['survey_id']) && isset($s['survey_id'])){
        if($results['survey_id'] != $s['survey_id']){
            unset($s);
        }
    }
}

if($survey){
    $data->events = array_values($survey);
} 

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_survey/userDashboard", $data);
echo $OUTPUT->footer();