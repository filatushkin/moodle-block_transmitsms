<?php
require_once("$CFG->libdir/formslib.php");

class transmitsms_form extends moodleform
{

    //Add elements to form
    public function definition()
    {
        global $CFG, $DB;

        $mform = $this->_form;

        $idnumbers = $this->_customdata['ids'];
        if ($idnumbers) {
            $idnumbers = explode(',', $idnumbers);
        }


        $students = $DB->get_records_sql("
            select id,phone1,firstname,lastname from {user} 
                where phone1 <> '' 
                order by firstname, lastname");

        $areanames[0] = 'Not Selected';
        $default = [];

        foreach ($students as $student) {

            //TODO:check country code and phone format
            if (strpos('+', $student->phone1)) continue;

            $areanames[$student->id] = $student->firstname . ' ' . $student->lastname;

            if ($idnumbers && in_array($student->id, $idnumbers)) {
                $default[] = $student->id;
            };
        }

        $options = array(
            'multiple' => true,
            //'noselectionstring' => "",                                                                
        );

        $mform->addElement('autocomplete', 'phones', "Recipient", $areanames, $options);

        $mform->addRule('phones', 'Recipient must be selected', 'required');
        $mform->setDefault('phones', implode(',', $default));


        $mform->addElement('textarea', 'sms', 'Message text', 'wrap="virtual" rows="5" cols="50" maxlength="400"');
        $mform->setType('sms', PARAM_TEXT);
        $mform->addRule('sms', 'Message text is required', 'required');
        $mform->addRule('sms', 'Message text min lenght is 10 chars', 'minlength', 10);
        $mform->addRule('sms', 'Message text max lenght is 400 chars', 'maxlength', 400);

        $buttonarray = array();

        $buttonarray[] = &$mform->createElement('submit', 'result', "Send");

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
