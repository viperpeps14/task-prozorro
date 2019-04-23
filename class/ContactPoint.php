<?php
class ContactPoint {
    public $name;
    public $email;
    public $telephone;
    public $faxNumber;
    public $url;
    
    public function addContact(){
        $sql = "INSERT INTO api_contactPoint("
            ."name,"
            ."email,"
            ."telephone,"
            ."faxNumber,"
            ."url)"
            . "VALUES(?, ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->name,
            $this->email,
            $this->telephone,
            $this->faxNumber,
            $this->url
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
