<?php
class Period {
    public $startDate;
    public $endDate;
    
    public function addPeriod(){
        $sql = "INSERT INTO api_period("
            ."startDate,"
            ."endDate)"
            . "VALUES(?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->startDate,
            $this->endDate
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
