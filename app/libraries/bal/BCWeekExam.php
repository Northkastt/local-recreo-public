<?php

abstract class BCWeekExam {

    private $db;
    protected $mainTable = "PlanningWeeks";
    protected $resultsTable = "NotSet";
    protected $gameType = -1;

    public function __construct() {
        
        $CI = & get_instance();
        $this->db = $CI->db;
    }

    public function exists($id) {
        $this->db->where('PlanningWeekId', $id);
        $this->db->from($this->mainTable);
        return $this->db->count_all_results() > 0;
    }

    public function studentHasResults($uniqueStudentId, $planningWeekId) {
        $student = $this->db->get_where("Students", array("UniqueStudentId" => $uniqueStudentId))->row_array();

        $this->db->where('PlanningWeekId', $planningWeekId);
        $this->db->where('StudentId', $student["StudentId"]);
        $this->db->from($this->resultsTable);
        return $this->db->count_all_results() > 0;
    }

    public function registerStudentResults($uniqueStudentId, $planningWeekId, $results, $startDateTime, $endDateTime) {
        $student = $this->db->get_where("Students", array("UniqueStudentId" => $uniqueStudentId))->row_array();

        $d = new DateTime();
        
        $realResults = array();

        foreach ($results as $item) {
            $realItem = array();
            $realItem["AnswerId"] = $item["AnswerId"];
            $realItem["StudentId"] = $item["AnswerId"];
            $realItem["PlanningWeekId"] = $planningWeekId;
            $realItem["SpentSeconds"] = $item["SpentSeconds"];
            $realItem["FailedAttempts"] = $item["FailedAttempts"];
            $realItem["StudentId"] = $student["StudentId"];
            
            $d->setTimestamp($item["AnswerDateTime"]);
            $realItem["AnswerDateTime"] = $d->format('Y-m-d H:i:s');
            
            array_push($realResults, $realItem);
        }
        
        $this->db->trans_start();

        $result = $this->db->insert_batch($this->resultsTable, $realResults);
        
        if(!$result){
            $this->db->trans_rollback();
            return false;
        }
        
        $d->setTimestamp($startDateTime);
        $startDateTime = $d->format('Y-m-d H:i:s');
        
        $d->setTimestamp($endDateTime);
        $endDateTime = $d->format('Y-m-d H:i:s');
        
        $data = array(
            "StartDateTime" => $startDateTime,
            "FinishDateTime" => $endDateTime,
            "StudentId" => $student["StudentId"],
            "PlanningWeekId" => $planningWeekId,
            "AnsweredOnWebClient" => 0,
            "LastAccessDateTime" => $d->format('Y-m-d H:i:s'),
            "IsFinished" => 1,
            "GameType" => $this->gameType
        );
        
        $result = $this->db->insert("WeekExamGameAttendances", $data);
        
        $this->db->trans_complete();
        
        return $result;
    }

    public function get($id) {
        $query = $this->db->get_where($this->mainTable, array("PlanningWeekId" => $id));
        $result = $query->row_array();

        $queryContent = $this->db->get_where("PlanningWeekContent", array("PlanningWeekId" => $id));
        $result["PlanningWeekContent"] = $queryContent->result_array();
        return $result;
    }

}