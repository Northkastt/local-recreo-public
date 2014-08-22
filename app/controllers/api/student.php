<?php

require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'libraries/bal/BCStudent.php');

class Student extends REST_Controller {

    public function register_post() {
        $this->load->helper('guid_helper');
        
        $component = new BCStudent();

        $uniqueStudentId = $this->post("uniqueStudentId");
        
        if(!is_valid_guid($uniqueStudentId)){
            $this->response(array('status' => 0, 'error' => 'Invalid unique student id.', 'result' => NULL), 400);
        }
        
        $gradeNumber = $this->post("gradeNumber");
        $groupName = $this->post("groupName");
        $schoolId = $this->post("schoolId");

        if ($component->exists($uniqueStudentId)) {
            $this->response(array('status' => 0, 'error' => 'Student id already exists.', 'result' => NULL), 400);
        }

        if ($component->register($uniqueStudentId, $gradeNumber, $groupName, $schoolId)) {
            $this->response(array('status' => 1, 'success' => 'Student has been created', 'result' => NULL), 200);
        } else {
            $this->response(array('status' => 0, 'error' => "Student was not created due to unknown error", 'result' => NULL), 400);
        }
    }

    public function exists_get() {
        $component = new BCStudent();

        $studentId = $this->get("studentId");

        $exists = $component->exists($studentId);

        $this->response(array('status' => 1, 'success' => 'Query executed', 'result' => $exists), 200);
    }

}

