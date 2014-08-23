<?php

class BCUploaderProxy {

    const PrivatePlanningBlockPreResults = 'p/private/planningblockpreresults?schoolId=%s';
    const PrivatePlanningBlockPostResults = 'p/private/planningblockpostresults?schoolId=%s';
    const PrivatePlanningWeekPreResults = 'p/private/planningweekpreresults?schoolId=%s';
    const PrivatePlanningWeekPostResults = 'p/private/planningweekpostresults?schoolId=%s';
    const PrivatePlanningWeekExerciseResults = 'p/private/planningweekexerciseresults?schoolId=%s';
    const PrivateStudentLogins = 'p/private/studentlogins?schoolId=%s';
    const PrivateNewStudents = 'p/private/newstudents?schoolId=%s';
    const PrivateNewDevices = 'p/private/newdevices?schoolId=%s';
    const PrivateHeartbeats = 'p/private/heartbeats?schoolId=%s';

    //put your code here
    private $config = array();
    private static $instance = NULL;
    private $ci = NULL;

    private function __construct() {
        $this->ci = & get_instance();
        ;
        $this->ci->load->spark('restclient/2.2.1');

        // Load the library
        $this->ci->load->library('rest');

        $this->config = array('server' => $this->ci->config->item('services_url'),
                //'api_key'         => 'Setec_Astronomy'
                //'api_name'        => 'X-API-KEY'
                //'http_user'       => 'username',
                //'http_pass'       => 'password',
                //'http_auth'       => 'basic',
                //'ssl_verify_peer' => TRUE,
                //'ssl_cainfo'      => '/certs/cert.pem'
        );

        $this->ci->rest->initialize($this->config);
    }

    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new BCUploaderProxy();
        }

        return self::$instance;
    }

    /**
     * 
     * @param type $schoolId
     * @param type $jsonObject
     * @return boolean
     */
    public static function uploadHeartbeats($schoolId, $jsonObject) {
        try {
            $request = $this->ci->rest->put(sprintf(self::PrivateHeartbeats, $schoolId), json_encode($jsonObject), 'application/json');
            return $request;
        } catch (Exception $ex) {
            //SyncLogger::writeErrorLog($ex->getMessage());
            //SyncLogger::writeErrorLog($ex->getTraceAsString());
            return false;
        }
    }

    /**
     * 
     * @param type $schoolId
     * @param type $jsonObject
     * @return boolean
     */
    public function uploadResults($schoolId, $jsonObject, $logType) {

        try {
            $serviceUrl = '';

            switch ($logType) {
                case BCUploader::PLANNING_BLOCK_POST_RESULTS:
                    $serviceUrl = self::PrivatePlanningBlockPostResults;
                    break;
                case BCUploader::PLANNING_BLOCK_PRE_RESULTS:
                    $serviceUrl = self::PrivatePlanningBlockPreResults;
                    break;
                case BCUploader::PLANNING_WEEK_POST_RESULTS:
                    $serviceUrl = self::PrivatePlanningWeekPostResults;
                    break;
                case BCUploader::PLANNING_WEEK_PRE_RESULTS:
                    $serviceUrl = self::PrivatePlanningWeekPreResults;
                    break;
                case BCUploader::PLANNING_WEEK_EXERCISE_RESULTS:
                    $serviceUrl = self::PrivatePlanningWeekExerciseResults;
                    break;
            }

            $this->ci->rest->debug();

            $request = $this->ci->rest->put(sprintf($serviceUrl, $schoolId), json_encode($jsonObject), 'application/json');

            return $request;
        } catch (Exception $ex) {
            //SyncLogger::writeErrorLog($ex->getMessage());
            //SyncLogger::writeErrorLog($ex->getTraceAsString());
            return false;
        }
    }

}