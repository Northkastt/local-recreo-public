<?php

require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'libraries/bal/BCStudent.php');
require(APPPATH . 'libraries/bal/BCWeekExercise.php');

class WeekExercise extends REST_Controller {

    public function info_get() {
        $component = new BCWeekExercise();

        $id = $this->get("id");
        $exerciseInfo = $component->get($id);

        if (!$component->exists($id)) {
            $this->response(array('status' => 0, 'error' => ERR_EXERCISE_NOT_FOUND, 'result' => NULL), 404);
        }

        $this->response(array('status' => 1, 'success' => 'Query executed', 'result' => $exerciseInfo), 200);
    }
    
    public function registerStudentExercise_post(){
        // Set params
        $uniqueStudentId = $this->post("uniqueStudentId");

        // Check if student exists
        $studentComponent = new BCStudent();
        if (!$studentComponent->exists($uniqueStudentId)) {
            $this->response(array('status' => 0, 'error' => ERR_STUDENT_NOT_FOUND, 'result' => NULL), 404);
        }

        $exerciseInfo = $this->post("exerciseInfo");
        
        $component = new BCWeekExercise();
        
        if (!$component->exists($uniqueStudentId, $exerciseInfo["ExternalId"])) {
            $this->response(array('status' => 0, 'error' => ERR_EXERCISE_ALREADY_EXISTS, 'result' => NULL), 400);
        }
    }

    public function registerStudentResults_post() {
        // Set params
        $uniqueStudentId = $this->post("uniqueStudentId");
        $id = $this->post("id");
        $results = $this->post("results");
        $startDateTime = $this->post("startDateTime");
        $endDateTime = $this->post("endDateTime");

        // Check if student exists
        $studentComponent = new BCStudent();
        if (!$studentComponent->exists($uniqueStudentId)) {
            $this->response(array('status' => 0, 'error' => ERR_STUDENT_NOT_FOUND, 'result' => NULL), 404);
        }

        $component = new BCWeekExercise();

        // Check if the exam exists
        if (!$component->exists($id)) {
            $this->response(array('status' => 0, 'error' => ERR_EXERCISE_NOT_FOUND, 'result' => NULL), 404);
        }

        // Check if the student has no previous results on this exam
        if ($component->studentHasResults($uniqueStudentId, $id)) {
            $this->response(array('status' => 0, 'error' => ERR_STUDENT_ALREADY_HAS_RESULTS, 'result' => NULL), 400);
        }

        // Register results
        if ($component->registerStudentResults($uniqueStudentId, $id, $results, $startDateTime, $endDateTime)) {
            $this->response(array('status' => 1, 'success' => 'Results were registered successfully', 'result' => null), 200);
        } else {
            $this->response(array('status' => 0, 'error' => ERR_UNABLE_TO_REGISTER_RESULTS, 'result' => null), 400);
        }
    }

}

