<?php
class Value {
    public $amount;
    public $currency;
    public $valueAddedTaxIncluded;
    
    public function addValue(){
        $sql = "INSERT INTO api_value("
            ."amount,"
            ."currency,"
            ."valueAddedTaxIncluded)"
            . "VALUES(?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->amount,
            $this->currency,
            $this->valueAddedTaxIncluded
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
