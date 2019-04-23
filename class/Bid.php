<?php
class Bid {
    public $id_tenderers_organization;
    public $id_api;
    public $status;
    public $id_value;
    public $id_documents;
    public $id_parameters;
    public $id_lotValues;
    public $participationUrl;
    
    public function addBid(){
        $sql = "INSERT INTO api_bid("
            ."id_tenderers_organization,"
            ."id_api,"
            ."status,"
            ."id_value,"
            ."id_documents,"
            ."id_parameters,"
            ."id_lotValues,"
            ."participationUrl)"
            . "VALUES(?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->id_tenderers_organization,
            $this->id_api,
            $this->status,
            $this->id_value,
            $this->id_documents,
            $this->id_parameters,
            $this->id_lotValues,
            $this->participationUrl
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
