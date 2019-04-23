<?php
class Address {
    public $streetAddress;
    public $locality;
    public $region;
    public $postalCode;
    public $countryName;
    
    public function addAddress(){
        $sql = "INSERT INTO api_address("
            ."streetAddress,"
            ."locality,"
            ."region,"
            ."postalCode,"
            ."countryName)"
            . "VALUES(?, ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->streetAddress,
            $this->locality,
            $this->region,
            $this->postalCode,
            $this->countryName
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
