<?php
class Classification {
    public $scheme;
    public $id_api;
    public $description;
    public $uri;
    
    public function addClassification(){
        $sql = "INSERT INTO api_classification("
            ."scheme,"
            ."id_api,"
            ."description,"
            ."uri)"
            . "VALUES(?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->scheme,
            $this->id_api,
            $this->description,
            $this->uri
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
