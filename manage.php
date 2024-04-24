<?php
require_once(__DIR__ . "/../../config.php");
$PAGE->set_url(new moodle_url('/local/survey/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('manage surveys');
$templatecontext = (object)[
    'texttodisplay' => 'here is some text',
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template("local_survey/manage", $templatecontext);

echo $OUTPUT->footer();