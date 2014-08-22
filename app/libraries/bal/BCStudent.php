<?php

class BCStudent {
    private $db;
    
    protected $mainTable = "Students";
    
    public function __construct() {
        $CI = & get_instance();
        $this->db = $CI->db;
    }
    
    public function exists($uniqueStudentId){
        $this->db->where('UniqueStudentId', $uniqueStudentId);
        $this->db->from($this->mainTable);
        return $this->db->count_all_results() > 0;
    }
    
    public function register($uniqueStudentId, $gradeNumber, $groupName, $schoolId){
        $data = array(
            "Email" => "NotSet",
            "FirstName" => "Brand",
            "LastName" => "new",
            "MothersName" => "student",
            "Gender" => "N",
            "BirthDate" => date('Y-m-d H:i:s'),
            "CreateDateTime" => date('Y-m-d H:i:s'),
            "Password" => "NotSet",
            "UniqueStudentId" => $uniqueStudentId,
            "Token" => "none",
            "GradeNumber" => $gradeNumber,
            "Group" => $groupName,
            "StudentNumber" => "0000000",
            "AzureId" => NULL,
            "SchoolId" => $schoolId
        );
        
        //$this->db->set('UniqueStudentId', 'UPPER(UUID())', false);
        
        return $this->db->insert($this->mainTable, $data);
    }
}