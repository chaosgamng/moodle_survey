<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/DAOS/AssignDAO.php");
require_once(__DIR__ . "/DAOS/conn.php");
require_once(__DIR__ . "/DAOS/SurveyDAO.php");
require_once(__DIR__ . "/DAOS/ResponseDAO.php");

$PAGE->set_url(new moodle_url('/local/survey/capture.php'));
$PAGE->set_context(\context_system::instance());
$con = new conn();
$rdao = new ResponseDAO($con);
$keys = array_keys($_POST);
if($_POST){
    foreach($_POST as $row){
        $rdao->insertResponse($keys[0], $USER->id, $row);
        array_splice($keys, 0, 1);      
    }
}

header("Location: /moodle/grade/report/grader/index.php?id=" . strval($COURSE->id));

?>