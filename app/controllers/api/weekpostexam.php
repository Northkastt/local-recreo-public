<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require(APPPATH . 'controllers/api/weekexam.php');

class WeekPostExam extends REST_Controller {
    /**
     *
     * @var WeekExam 
     */
    protected $controllerBehaviour;


    public function __construct() {
        $this->controllerBehaviour = new WeekExam();
        $this->controllerBehaviour->setComponentInstance(new BCWeekPostExam());
        parent::__construct();
    }
    
    public function info_get() {
        $this->controllerBehaviour->info_get();
    }
    
    public function registerStudentStart_post() {
        $this->controllerBehaviour->registerStudentStart_post();
    }

    public function registerStudentResults_post() {
        $this->controllerBehaviour->registerStudentResults_post();
    }
}