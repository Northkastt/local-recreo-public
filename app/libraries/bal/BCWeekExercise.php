<?php

class BCWeekExercise extends BCWeekExam {

    public function __construct() {
        $this->resultsTable = "PlanningWeekExerciseResults";
        $this->mainTable = "PlanningWeekExercises";
        $this->gameType = 1;
        parent::__construct();
    }

    public function getPlanningWeekExercise($uniqueStudentId, $externalId) {
        $student = $this->db->get_where("Students", array("UniqueStudentId" => $uniqueStudentId))->row_array();
        $entity = $this->db->get_where($this->mainTable, array("ExternalId" => $externalId, "StudentId" => $student["StudentId"]))->row_array();

        return $entity;
    }

    public function get($uniqueStudentId, $externalId) {
        $planningWeekExercise = $this->getPlanningWeekExercise($uniqueStudentId, $externalId);

        $queryContent = $this->db->get_where("PlanningWeekExerciseContent", array("PlanningWeekExerciseId" => $planningWeekExercise["PlanningWeekExerciseId"]));
        $planningWeekExercise["PlanningWeekExerciseContent"] = $queryContent->result_array();
        return $planningWeekExercise;
    }

    public function exists($uniqueStudentId, $externalId) {
        $student = $this->db->get_where("Students", array("UniqueStudentId" => $uniqueStudentId))->row_array();

        $this->db->where('ExternalId', $externalId);
        $this->db->where('StudentId', $student["StudentId"]);
        $this->db->from($this->mainTable);
        return $this->db->count_all_results() > 0;
    }

    public function registerStudentExercise($uniqueStudentId, $modelObject) {
        $student = $this->db->get_where("Students", array("UniqueStudentId" => $uniqueStudentId))->row_array();

        $d = new DateTime();
        $d->setTimestamp($modelObject["ExerciseDate"]);
        $d->format('Y-m-d H:i:s');

        $data = array(
            "ExternalId" => $modelObject["ExternalId"],
            "PlanningWeekId" => $modelObject["PlanningWeekId"],
            "ExerciseDate" => $d->format('Y-m-d H:i:s'),
            "QuestionsCount" => $modelObject["QuestionsCount"],
            "StudentId" => $student["StudentId"]
        );

        $content = $modelObject["Content"];

        $this->db->trans_start();

        $this->db->insert($this->mainTable, $data);

        $planningWeekExerciseId = $this->db->insert_id();

        // Insert content
        foreach ($content as $item) {
            $contentData = array(
                "PlanningWeekExerciseId" => $planningWeekExerciseId,
                "IndicatorId" => $item["IndicatorId"],
                "TagId" => $item["TagId"],
                "PreTagScore" => 0 // TODO: Add real implementation
            );

            $this->db->insert("PlanningWeekExerciseContent", $contentData);
        }

        $this->db->trans_complete();

        return true;
    }

    public function registerStudentResults($uniqueStudentId, $externalId, $results, $startDateTime, $endDateTime) {
        $planningWeekExercise = $this->getPlanningWeekExercise($uniqueStudentId, $externalId);
        $student = $this->db->get_where("Students", array("UniqueStudentId" => $uniqueStudentId))->row_array();

        $d = new DateTime();

        $realResults = array();

        //Prepare results
        foreach ($results as $item) {
            $realItem = array();
            $realItem["AnswerId"] = $item["AnswerId"];
            $realItem["StudentId"] = $item["AnswerId"];
            $realItem["PlanningWeekExerciseId"] = $planningWeekExercise["PlanningWeekExerciseId"];
            $realItem["SpentSeconds"] = $item["SpentSeconds"];
            $realItem["FailedAttempts"] = $item["FailedAttempts"];
            $realItem["StudentId"] = $student["StudentId"];

            $d->setTimestamp($item["AnswerDateTime"]);
            $realItem["AnswerDateTime"] = $d->format('Y-m-d H:i:s');

            array_push($realResults, $realItem);
        }

        $this->db->trans_start();

        $result = $this->db->insert_batch($this->resultsTable, $realResults);

        if (!$result) {
            $this->db->trans_rollback();
            return false;
        }
        
        // Insert attendance
        $d->setTimestamp($startDateTime);
        $startDateTime = $d->format('Y-m-d H:i:s');

        $d->setTimestamp($endDateTime);
        $endDateTime = $d->format('Y-m-d H:i:s');

        $data = array(
            "StartDateTime" => $startDateTime,
            "FinishDateTime" => $endDateTime,
            "StudentId" => $student["StudentId"],
            "PlanningWeekExerciseId" => $planningWeekExercise["PlanningWeekExerciseId"],
            "PlanningWeekId" => $planningWeekExercise["PlanningWeekId"],
            "AnsweredOnWebClient" => 0,
            "LastAccessDateTime" => $d->format('Y-m-d H:i:s'),
            "IsFinished" => 1,
            "GameType" => $this->gameType
        );

        $result = $this->db->insert("WeekExamGameAttendances", $data);

        $this->db->trans_complete();

        return $result;
    }

}