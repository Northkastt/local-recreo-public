<?php

class DbUploaderRepository {

    private $ci;

    public function __construct($ci) {
        $this->ci = $ci;
    }

    public function markHeartbeatAsReported($id) {
        $nowDateTime = date('Y-m-d H:i:s', time());

        $data = array("ReportedDateTime" => $nowDateTime, "WasReported" => 1);

        $this->ci->db->where('HeartbeatId', $id);

        if (!$this->ci->db->update('Heartbeats', $data)) {
            //SyncLogger::writeErrorLog("Couldn't mark heartbeat as reported. Id: $id");
        } else {
            //SyncLogger::writeInfoLog(sprintf("Heartbeat marked as Reported. ID: $id"));
        }
    }

    public function getNotReportedHeartbeats() {
        $maxRowsToUpload = $this->ci->config->item("services_max_rows_to_upload");

        $notReportedHeartbeats = $this->ci->db->query("
            SELECT * FROM Heartbeats WHERE WasReported = (0) LIMIT $maxRowsToUpload
        ");

        return $notReportedHeartbeats->result_array();
    }

    /**
     * 
     * @param type $lastRecordId
     * @param type $logType
     */
    public function saveSync($lastRecordId, $logType) {
        $nowDateTime = date('Y-m-d H:i:s', time());

        $data = array
            (
            "LogType" => $logType,
            "LastRecordId" => $lastRecordId,
            "SyncDateTime" => $nowDateTime
        );

        if (!$this->ci->db->insert("CloudSyncLog", $data)) {
            //SyncLogger::writeErrorLog(var_export($this->db->errorInfo(), true));
        } else {
            //SyncLogger::writeInfoLog(sprintf("Synced $logType with last record id: $lastRecordId"));
        }
    }

    /**
     * 
     * @param type $tableName
     * @param type $primaryColumn
     * @param type $logType
     * @param type $maxUploadedId
     * @return type
     */
    public function getUnSyncedResults($tableName, $primaryColumn, $logType, &$maxUploadedId) {

        $maxRowsToUpload = $this->ci->config->item("services_max_rows_to_upload");

        $arrayToRemove = array("Pre", "Post", "Exercise");

        $primaryCloudId = str_replace($arrayToRemove, '', $primaryColumn);

        // Get max id in the results table
        $maxIdOnOriginTable = $this->ci->db->query("SELECT MAX($primaryColumn) AS result FROM $tableName")->row_array();
        $maxTableId = floatval($maxIdOnOriginTable["result"]);

        // Get max id in the syncing table
        $maxIdSyncedRow = $this->ci->db->query("SELECT MAX(LastRecordId) AS result FROM CloudSyncLog WHERE LogType = ?", array($logType))->row_array();
        $maxSyncedId = floatval($maxIdSyncedRow["result"]);

        $result = array();

        if ($maxSyncedId < $maxTableId) {
            //SyncLogger::writeInfoLog("There are  $logType to Upload");
            $maxRowToBeUploaded = $this->ci->db->query("
                SELECT MAX($primaryColumn) AS result
                FROM(
                SELECT p.$primaryColumn FROM $tableName p 
				LEFT JOIN Devices d ON p.DeviceId = d.DeviceId
                LEFT JOIN Students s ON p.StudentId = s.StudentId
				WHERE $primaryColumn > $maxSyncedId AND p.StudentId IS NOT NULL
                ORDER BY $primaryColumn
                LIMIT $maxRowsToUpload
                ) as T1
            ")->row_array();

            $maxUploadedId = floatval($maxRowToBeUploaded["result"]);

            if ($tableName == "PlanningWeekExerciseResults") {
                $rowsToSync = $this->ci->db->query(
                        "SELECT p.*, p.$primaryColumn AS $primaryCloudId, pe.PlanningWeekId AS PlanningWeekId, s.UniqueStudentId, d.DeviceUniqueId FROM $tableName p 
				LEFT JOIN Devices d ON p.DeviceId = d.DeviceId
                LEFT JOIN Students s ON p.StudentId = s.StudentId
                JOIN PlanningWeekExercises pe ON p.PlanningWeekExerciseId = pe.PlanningWeekExerciseId
				WHERE $primaryColumn > $maxSyncedId 
                ORDER BY $primaryColumn
                LIMIT $maxRowsToUpload");
            } else {
                $rowsToSync = $this->ci->db->query(
                        "SELECT p.*, p.$primaryColumn AS $primaryCloudId, s.UniqueStudentId, d.DeviceUniqueId FROM $tableName p 
				LEFT JOIN Devices d ON p.DeviceId = d.DeviceId
                LEFT JOIN Students s ON p.StudentId = s.StudentId
				WHERE $primaryColumn > $maxSyncedId AND p.StudentId IS NOT NULL
                ORDER BY $primaryColumn
                LIMIT $maxRowsToUpload");
            }

            $result = $rowsToSync->result_array();
        }

        return $result;
    }

}

?>
