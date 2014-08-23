<?php

require_once APPPATH . 'libraries/dal/DbLocalWorkerRoleRepository.php';

class BCLocalWorkerRole {

    /**
     *
     * @var DbLocalWorkerRoleRepository
     */
    private $dbRepository;

    /**
     *
     * @var BCLocalWorkerRole 
     */
    private static $instance = NULL;
    private $ci;

    /**
     * 
     * @return BCLocalWorkerRole
     */
    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new BCLocalWorkerRole();
        }

        return self::$instance;
    }

    private function __construct() {
        $CI = & get_instance();

        $this->ci = $CI;
        $this->dbRepository = new DbLocalWorkerRoleRepository($this->ci);
    }

    public function generateHeartbeat() {
        $this->dbRepository->generateHeartbeat();
    }

}

?>
