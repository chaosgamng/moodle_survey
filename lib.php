<?php

function local_message_before_footer(){
    \core\notification::add("a test message", \core\output\notification::NOTIFY_SUCCESS);
}
