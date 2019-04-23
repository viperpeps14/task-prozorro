<?php
class Unit {
    public $code;
    public $name;
    
    public function addUnit(){
        $sql = "INSERT INTO api_unit("
            ."code,"
            ."name)"
            . "VALUES(?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->code,
            $this->name
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
