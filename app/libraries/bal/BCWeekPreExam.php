<?php

class BCWeekPreExam extends BCWeekExam{

    public function __construct() {
        $this->resultsTable = "PlanningWeekPreResults";
        $this->gameType = 0;
        parent::__construct();
    }

}