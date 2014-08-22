<?php

class BCWeekPostExam extends BCWeekExam{

    public function __construct() {
        $this->resultsTable = "PlanningWeekPostResults";
        $this->gameType = 2;
        parent::__construct();
    }

}