<?php

require_once('../../config.php');

require_login();
require_capability(
    'block/transmitsms:view',
    context_system::instance()
);

$studentids = optional_param('ids', false, PARAM_TEXT);

//if($studentids)
//    $studentids = explode(',',$studentids);

$current_url_part = '/blocks/transmitsms/index.php';
$pagetitle = get_string('pluginname', 'block_transmitsms');

$url = new moodle_url($current_url_part);

$PAGE->set_url($url);

$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_system::instance());
$PAGE->set_title($pagetitle);

$node = $PAGE->navigation->add(
    $pagetitle,
    new moodle_url($current_url_part),
    navigation_node::TYPE_CONTAINER
);

$node->make_active();


echo $OUTPUT->header();

echo html_writer::start_tag('h2', array('class' => 'header'));
echo $pagetitle;
echo html_writer::end_tag('h2', array('class' => 'header'));

//TODO: fix class not found
$api = new \transmitsms();
$balance = $api->getBalance();
if ($balance->error->code == 'SUCCESS') {
    $amount = (int)($balance->balance / 0.079);
    echo '<p>SMS account balance: <b>' . $balance->balance . ' ' . $balance->currency . '</b>. 
    The cost of each SMS is from 7.9 to 4.9 cents. The balance is enough for sending at least ' . $amount . ' more SMSs</p>';
}

require_once(__DIR__ . '/classes/transmitsms_form.php');
$mform = new transmitsms_form($url, array('ids' => $studentids));

$fromform = $mform->get_data();


if ($fromform && isset($fromform->phones)) {

    $message = "SMS message has been sent";

    foreach ($fromform->phones as $userid) {


        $touser = $DB->get_record('user', array('id' => $userid));

        //TODO: fix not found, check api call
        // sending to a set of numbers
        $result = $api->sendSms($fromform->sms,$touser->phone1,'callerid');

        if ($result->error->code != 'SUCCESS') {
            $message = "Moodle cannot send SMS messages. Please contact the IT team!";
        }
    }

    redirect($CFG->wwwroot . $current_url_part, $message, 10);
} else if ($fromform && !isset($fromform->phones)) {

    $message = "SMS message not sent! At least 1 Recipient must be selected!";
    redirect($CFG->wwwroot . $current_url_part, $message, 5);
} else {
    $mform->display();
}

echo $OUTPUT->footer();
