<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");

$PAGE->set_url(new moodle_url('/local/survey/takeSurvey.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('survey page');
$data = $_REQUEST['id'];

echo $OUTPUT->header();
echo "<script> console.log(" . $data . "); </script>";
echo $OUTPUT->render_from_template("local_survey/takeSurvey", $data);
echo $OUTPUT->footer();