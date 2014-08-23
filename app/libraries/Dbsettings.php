<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbSettings
 *
 * @author ubuntu
 */
class Dbsettings {

    //put your code here
    public function __construct() {
        $CI = & get_instance();
        $results = $CI->db->get('Settings')->result();

        foreach ($results as $setting) {

            $CI->config->set_item($setting->SettingName, $setting->SettingValue);
        }

        $results = $CI->db->get('Schools')->result();
        $schoolIdsValue = array();
        $schoolsInfo = array();

        $masterSchoolId = 1;

        foreach ($results as $school) {
            array_push($schoolIdsValue, $school->SchoolId);

            if ($school->IsMaster == 1) {
                $masterSchoolId = $school->SchoolId;
            }
            
            $schoolsInfo[$school->SchoolId] = $school;
        }

        $CI->config->set_item("school_ids", $schoolIdsValue);
        $CI->config->set_item("school_metas", $schoolsInfo);
        $CI->config->set_item("master_school_id", $masterSchoolId);
    }
}

?>
