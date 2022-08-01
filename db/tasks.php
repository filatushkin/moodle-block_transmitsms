<?php


defined('MOODLE_INTERNAL') || die();

$tasks = array(

    array(
        'classname' => 'block_transmitsms\task\low_sms_balance_notification_task',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '4',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*'

    )

);
