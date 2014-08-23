<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'libraries/bal/BCUploader.php';

class CloudWorkerRole extends CI_Controller {

    public function upload() {
        if ($this->input->is_cli_request()) {
            $uploader = BCUploader::getInstance();

            echo "\n Trying to upload Planning Week Pre Results";
            $uploader->tryUploadPlanningWeekPreResults();

            echo "\n Trying to upload Planning Week Exercise Results";
            $uploader->tryUploadPlanningWeekExerciseResults();

            echo "\n Trying to upload Planning Week Post Results";
            $uploader->tryUploadPlanningWeekPostResults();

            echo "\n Trying to upload Planning Block Pre Results";
            $uploader->tryUploadPlanningBlockPreResults();

            echo "\n Trying to upload Planning Block Post Results";
            $uploader->tryUploadPlanningBlockPostResults();

            echo "\n Trying to upload Heartbeats";
            $uploader->tryUploadHeartbeats();

            echo "\n Done!" . PHP_EOL;
        } else {
            show_error("Forbidden action");
        }
    }

}
