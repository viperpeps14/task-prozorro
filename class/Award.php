<?php
class Award {
    
    public $id_api;
    public $bid_id;
    public $title;
    public $description;
    public $status;
    public $date;
    public $id_value;
    public $id_suppliers;
    public $id_items;
    public $id_documents;
    public $id_complaints;
    public $id_complaintPeriod;
    public $id_lotID;
    
    public function addAward(){
        $sql = "INSERT INTO api_award("
            ."id_api,"
            ."bid_id,"
            ."title,"
            ."description,"
            ."status,"
            ."id_value,"
            ."id_suppliers,"
            ."id_items,"
            ."id_documents,"
            ."id_complaints,"
            ."id_complaintPeriod,"        
            ."id_lotID)"
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->id_api,
            $this->bid_id,
            $this->title,
            $this->description,
            $this->status,
            $this->date,
            $this->id_value,
            $this->id_suppliers,
            $this->id_items,
            $this->id_documents,
            $this->id_complaints,
            $this->id_complaintPeriod,
            $this->id_lotID
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
