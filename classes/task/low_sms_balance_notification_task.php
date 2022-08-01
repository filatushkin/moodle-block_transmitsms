<?php

namespace block_transmitsms\task;

class low_sms_balance_notification_task extends \core\task\scheduled_task
{

    //TODO: lang string
    public function get_name()
    {
        return 'Low SMS Balance notification task';
    }

    public function execute()
    {
        global $CFG, $DB;

        //TODO: fix not found
        $api = new \transmitsmsAPI();
        $r = $api->getBalance();

        if ($r->error->code == 'SUCCESS') {
            if ($r->balance < 10) {
                echo 'Balance is ' . $r->balance . ' ' . $r->currency . '. Need to Recharge. Sending notification email!';

                //send email notiifcation
                //TODO: fix user search by email
                $user = $from = $DB->get_record('user', ['email' => 'USER_EMAIL']);

                email_to_user(
                    $user,
                    $from,
                    'Low SMS Balance',
                    'Hi Support, Low balance is on Moodle SMS account service. Please, pay to renew the service. https://burst.transmitsms.com/',
                    'Hi Support, Low balance is on Moodle SMS account service. Please, pay to renew the service. https://burst.transmitsms.com/'
                );
            } else {
                echo 'Balance is ' . $r->balance . ' ' . $r->currency . '. No need to Recharge.';
            }
        }
    }
}
