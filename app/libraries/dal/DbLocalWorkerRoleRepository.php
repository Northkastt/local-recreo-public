<?php

class DbLocalWorkerRoleRepository {

    private $ci;

    public function __construct($ci) {
        $this->ci = $ci;
    }

    public function generateHeartbeat() {
        $nowDateTime = date('Y-m-d H:i:s', time());

        $weekPreResults = $this->pendingResults("PlanningWeekPreResults", "PlanningWeekPreResultId");
        $weekExcerciseResults = $this->pendingResults("PlanningWeekExerciseResults", "PlanningWeekExerciseResultId");
        $weekPostResults = $this->pendingResults("PlanningWeekPostResults", "PlanningWeekPostResultId");
        $blockPreResults = $this->pendingResults("PlanningBlockPreResults", "PlanningBlockPreResultId");
        $blockPostResults = $this->pendingResults("PlanningBlockPostResults", "PlanningBlockPostResultId");

        $data = array(
            "GeneratedDateTime" => $nowDateTime,
            "PendingWeekPreResults" => $weekPreResults,
            "PendingWeekExcerciseResults" => $weekExcerciseResults,
            "PendingWeekPostResults" => $weekPostResults,
            "PendingBlockPreResults" => $blockPreResults,
            "PendingBlockPostResults" => $blockPostResults
        );

        if (!$this->ci->db->insert('Heartbeats', $data)) {
            //SyncLogger::writeErrorLog(var_export($this->db->errorInfo(), true));
        } else {
            //SyncLogger::writeInfoLog(sprintf("Heartbeat generated."));
        }
    }

    public function pendingResults($tableName, $primaryColumn) {
        $maxIdSynced = $this->ci->db->query("SELECT MAX(LastRecordId) AS result FROM CloudSyncLog WHERE LogType = ?", array($tableName))->row_array();

        $maxSyncedId = floatval($maxIdSynced["result"]);

        $queryCount = $this->ci->db->query("SELECT COUNT(*) AS result FROM $tableName WHERE $primaryColumn > $maxSyncedId")->row_array();

        $result = floatval($queryCount["result"]);

        return $result;
    }

}

?>
