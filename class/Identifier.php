<?php
class Identifier {
    public $scheme;
    public $id_api;
    public $legalName;
    public $uri;
    
    public function addIdentifier(){
        $sql = "INSERT INTO api_identifier("
            ."scheme,"
            ."id_api,"
            ."legalName,"
            ."uri)"
            . "VALUES(?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->scheme,
            $this->id_api,
            $this->legalName,
            $this->uri
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
