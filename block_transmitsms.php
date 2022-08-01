<?php

//this is standard moodle block class
//it should be created for any moodle block
//basically name is always the following:
//block_BLOCKNAME

//More info here - https://docs.moodle.org/dev/Blocks

class block_transmitsms extends block_base
{

    //init function used for preparing block title
    //and made some preparations
    function init()
    {
        $this->title = get_string('pluginname', 'block_transmitsms');
    }

    //allow of dissalow configuration
    /*function has_config() {
        return true;
    } */

    //allows to put bloc in any page of the Moodle
    //limiations and possible settings - https://docs.moodle.org/dev/Blocks
    function applicable_formats()
    {
        return array('all' => true);
    }

    //This "magic" method has actually a very nice property: 
    //it's guaranteed to be automatically called by Moodle 
    //as soon as our instance configuration is loaded and 
    //available (that is, immediately after init() is called).

    function specialization()
    {
        $this->title = isset($this->config->title) ? format_string($this->config->title) : format_string(get_string('newhtmlblock', 'block_transmitsms'));
    }

    //if we want to have only 1 instance in the moodle
    //than we should set it to the false
    function instance_allow_multiple()
    {
        return true;
    }


    //This is a function wich made the output
    //for the block html content
    //The main thing that its doing - is
    //taking all data which we need and 
    //preapare JS ans CSS files, and than generate the content
    function get_content()
    {
        global $CFG, $DB, $PAGE, $USER;


        //standard line, Moodle requires it
        if ($this->content !== NULL) {
            return $this->content;
        }

        //preparing content object
        $this->content = new stdClass;

        $this->content->text =  '';

        //TODO: lang strings fix
        if (has_capability('block/transmitsms:view', context_system::instance())) {
            $this->content->text =  '<input type="button" class="btn btn-primary" onclick="window.open(\'' . $CFG->wwwroot . '/blocks/transmitsms/index.php\',\'_blank\');" value="Send SMS" />';
        }

        //send content
        return $this->content;
    }
}
