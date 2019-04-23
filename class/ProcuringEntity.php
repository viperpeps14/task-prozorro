<?php
class ProcuringEntity {
    public $name;
    public $id_identifier;
    public $id_additionalIdentifiers;
    public $id_address;
    public $id_contactPoint;
    public $king;
    
    public function addProcuring(){
        $sql = "INSERT INTO api_ProcuringEntity("
            ."name,"
            ."id_identifier,"
            ."id_additionalIdentifiers,"
            ."id_address,"
            ."id_contactPoint,"
            ."king)"
            . "VALUES(?, ?, ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->name,
            $this->id_identifier,
            $this->id_additionalIdentifiers,
            $this->id_address,
            $this->id_contactPoint,
            $this->king
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
