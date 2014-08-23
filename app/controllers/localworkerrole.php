<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'libraries/bal/BCLocalWorkerRole.php';

class LocalWorkerRole extends CI_Controller {

    public function generateHeartbeat() {
        if ($this->input->is_cli_request()) {
            $component = BCLocalWorkerRole::getInstance();

            echo "\n Generating heartbeat";
            $component->generateHeartbeat();

            echo "\n Done!" . PHP_EOL;
        } else {
            show_error("Forbidden action");
        }
    }

}
