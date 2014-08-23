<?php

require_once APPPATH . 'libraries/bal/BCUploaderProxy.php';
require_once APPPATH . 'libraries/dal/DbUploaderRepository.php';

class BCUploader {

    const PLANNING_BLOCK_PRE_RESULTS = 'PlanningBlockPreResults';
    const PLANNING_BLOCK_POST_RESULTS = 'PlanningBlockPostResults';
    const PLANNING_WEEK_PRE_RESULTS = 'PlanningWeekPreResults';
    const PLANNING_WEEK_EXERCISE_RESULTS = 'PlanningWeekExerciseResults';
    const PLANNING_WEEK_POST_RESULTS = 'PlanningWeekPostResults';
    const STUDENT_LOGINS = 'StudentLogins';

    /**
     *
     * @var DbUploaderRepository
     */
    private $dbUploaderRepository;

    /**
     *
     * @var BCUploaderProxy 
     */
    private $uploaderProxy;
    private static $instance = NULL;
    private $ci;

    /**
     * 
     * @return BCUploader
     */
    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new BCUploader();
        }

        return self::$instance;
    }

    private function __construct() {
        $CI = & get_instance();

        $this->ci = $CI;

        $this->dbUploaderRepository = new DbUploaderRepository($this->ci);
        $this->uploaderProxy = BCUploaderProxy::getInstance();
    }

    public function tryUploadPlanningWeekPreResults() {
        $maxUploadedId = 0;
        $items = $this->dbUploaderRepository->getUnSyncedResults('PlanningWeekPreResults', 'PlanningWeekPreResultId', self::PLANNING_WEEK_PRE_RESULTS, $maxUploadedId);

        if (count($items) > 0) {
            if ($this->uploaderProxy->uploadResults($this->ci->config->item("master_school_id"), $items, self::PLANNING_WEEK_PRE_RESULTS)) {
                $this->dbUploaderRepository->saveSync($maxUploadedId, self::PLANNING_WEEK_PRE_RESULTS);
            }
        }
    }

    public function tryUploadPlanningWeekExerciseResults() {
        $maxUploadedId = 0;
        $items = $this->dbUploaderRepository->getUnSyncedResults('PlanningWeekExerciseResults', 'PlanningWeekExerciseResultId', self::PLANNING_WEEK_EXERCISE_RESULTS, $maxUploadedId);

        if (count($items) > 0) {
            if ($this->uploaderProxy->uploadResults($this->ci->config->item("master_school_id"), $items, self::PLANNING_WEEK_EXERCISE_RESULTS)) {
                $this->dbUploaderRepository->saveSync($maxUploadedId, self::PLANNING_WEEK_EXERCISE_RESULTS);
            }
        }
    }

    public function tryUploadPlanningWeekPostResults() {
        $maxUploadedId = 0;
        $items = $this->dbUploaderRepository->getUnSyncedResults('PlanningWeekPostResults', 'PlanningWeekPostResultId', self::PLANNING_WEEK_POST_RESULTS, $maxUploadedId);

        if (count($items) > 0) {
            if ($this->uploaderProxy->uploadResults($this->ci->config->item("master_school_id"), $items, self::PLANNING_WEEK_POST_RESULTS)) {
                $this->dbUploaderRepository->saveSync($maxUploadedId, self::PLANNING_WEEK_POST_RESULTS);
            }
        }
    }

    public function tryUploadPlanningBlockPostResults() {
        $maxUploadedId = 0;
        $items = $this->dbUploaderRepository->getUnSyncedResults('PlanningBlockPostResults', 'PlanningBlockPostResultId', self::PLANNING_BLOCK_POST_RESULTS, $maxUploadedId);

        if (count($items) > 0) {
            if ($this->uploaderProxy->uploadResults($this->ci->config->item("master_school_id"), $items, self::PLANNING_BLOCK_POST_RESULTS)) {
                $this->dbUploaderRepository->saveSync($maxUploadedId, self::PLANNING_BLOCK_POST_RESULTS);
            }
        }
    }

    public function tryUploadPlanningBlockPreResults() {
        $maxUploadedId = 0;
        $items = $this->dbUploaderRepository->getUnSyncedResults('PlanningBlockPreResults', 'PlanningBlockPreResultId', self::PLANNING_BLOCK_PRE_RESULTS, $maxUploadedId);

        if (count($items) > 0) {
            if ($this->uploaderProxy->uploadResults($this->ci->config->item("master_school_id"), $items, self::PLANNING_BLOCK_PRE_RESULTS)) {
                $this->dbUploaderRepository->saveSync($maxUploadedId, self::PLANNING_BLOCK_PRE_RESULTS);
            }
        }
    }

    public function tryUploadHeartbeats() {
        $notReportedHeartbeats = $this->dbUploaderRepository->getNotReportedHeartbeats();

        if (count($notReportedHeartbeats) > 0) {
            // Update statusl
            //SyncLogger::writeInfoLog(sprintf("Will upload %s heartbeat(s)", count($notReportedHeartbeats)));

            if ($this->uploaderProxy->uploadHeartbeats($this->ci->config->item("master_school_id"), $notReportedHeartbeats) !== false) {
                foreach ($notReportedHeartbeats as $heartbeat) {
                    $this->dbUploaderRepository->markHeartbeatAsReported($heartbeat["HeartbeatId"]);
                }
            }
        }
    }

}

?>
