<?php
class Items {
    public $id_api;
    public $description;
    public $classification;
    public $id_additionalClassifications;
    public $id_unit;
    public $quantity;
    public $id_deliveryDate;
    public $id_deliveryAddress;
    public $id_deliveryLocation;
    public $id_relatedLot;
    
    public function addItems(){
        $sql = "INSERT INTO api_item("
            ."id_api,"
            ."description,"
            ."classification,"
            ."id_additionalClassifications,"
            ."id_unit,"
            ."quantity,"
            ."id_deliveryDate,"
            ."id_deliveryAddress,"
            ."id_deliveryLocation,"
            ."id_relatedLot)"
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->id_api,
            $this->description,
            $this->classification,
            $this->id_additionalClassifications,
            $this->id_unit,
            $this->quantity,
            $this->id_deliveryDate,
            $this->id_deliveryAddress,
            $this->id_deliveryLocation,
            $this->id_relatedLot
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
