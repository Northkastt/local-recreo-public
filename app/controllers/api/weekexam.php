<?php

require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'libraries/bal/BCStudent.php');
require(APPPATH . 'libraries/bal/BCWeekExam.php');
require(APPPATH . 'libraries/bal/BCWeekPreExam.php');
require(APPPATH . 'libraries/bal/BCWeekPostExam.php');

class WeekExam extends REST_Controller {

    /**
     *
     * @var BCWeekExam 
     */
    protected $componentInstance;

    public function setComponentInstance($componentInstance) {
        $this->componentInstance = $componentInstance;
    }

    public function info_get() {
        $component = $this->componentInstance;

        $id = $this->get("id");
        $examInfo = $component->get($id);

        if (!$component->exists($id)) {
            $this->response(array('status' => 0, 'error' => ERR_PLANNING_WEEK_NOT_FOUND, 'result' => NULL), 404);
        }

        $this->response(array('status' => 1, 'success' => 'Query executed', 'result' => $examInfo), 200);
    }

    public function registerStudentStart_post() {
        $uniqueStudentId = $this->post("uniqueStudentId");
        $id = $this->post("id");
        $startDateTime = $this->post("startDateTime");

        $studentComponent = new BCStudent();

        if (!$studentComponent->exists($uniqueStudentId)) {
            $this->response(array('status' => 0, 'error' => ERR_STUDENT_NOT_FOUND, 'result' => NULL), 404);
        }

        $component = $this->componentInstance;

        // Check if the exam exists
        if (!$component->exists($id)) {
            $this->response(array('status' => 0, 'error' => ERR_PLANNING_WEEK_NOT_FOUND, 'result' => NULL), 404);
        }

        // Check if the student has no previous results on this exam
        if ($component->studentHasResults($uniqueStudentId, $id)) {
            $this->response(array('status' => 0, 'error' => ERR_STUDENT_ALREADY_HAS_RESULTS, 'result' => NULL), 400);
        }
        // Register student starting the game
        if ($component->registerStudentStart($uniqueStudentId, $id, $startDateTime)) {
            $this->response(array('status' => 1, 'success' => 'Student start has been registered', 'result' => null), 200);
        } else {
            $this->response(array('status' => 0, 'error' => ERR_UNABLE_TO_REGISTER_RESULTS, 'result' => NULL), 400);
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

        $component = $this->componentInstance;

        // Check if the exam exists
        if (!$component->exists($id)) {
            $this->response(array('status' => 0, 'error' => ERR_PLANNING_WEEK_NOT_FOUND, 'result' => NULL), 404);
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

