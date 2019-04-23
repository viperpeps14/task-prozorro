<?php
class Tender {
    public $title;
    public $description;
    public $tenderID;
    public $id_procuringEntity;
    public $id_value;
    public $id_guarantee;
    public $date;
    public $id_features;
    public $id_group_documents;
    public $id_questions;
    public $id_complaints;
    public $id_bids;
    public $id_value_minimalStep;
    public $id_awards;
    public $id_group_contracts;
    public $id_enquiryPeriod;
    public $id_tenderPeriod;
    public $id_auctionPeriod;
    public $auctionUrl;
    public $id_awardPeriod;
    public $status;
    public $id_group_lots;
    public $id_group_cancellations;
    public $id_group_funders;
    public $id_group_revisions;
    
    public function addTender(){
        $sql = "INSERT INTO api_tender("
            ."title,"
            ."description,"
            ."tenderID,"
            ."id_procuringEntity,"
            ."id_value,"
            ."id_guarantee,"
            ."date,"
            ."id_items,"
            ."id_features,"
            ."id_group_documents,"
            ."id_questions,"
            ."id_complaints,"
            ."id_bids,"
            ."id_value_minimalStep,"
            ."id_awards,"
            ."id_group_contracts,"
            ."id_enquiryPeriod,"
            ."id_tenderPeriod,"
            ."id_auctionPeriod,"
            ."auctionUrl,"
            ."id_awardPeriod,"
            ."status,"
            ."id_group_lots,"
            ."id_group_cancellations,"
            ."id_group_funders,"
            ."id_group_revisions)"
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->title,
            $this->description,
            $this->tenderID,
            $this->id_procuringEntity,
            $this->id_value,
            $this->id_guarantee,
            $this->date,
            $this->id_items,
            $this->id_features,
            $this->id_group_documents,
            $this->id_questions,
            $this->id_complaints,
            $this->id_bids,
            $this->id_value_minimalStep,
            $this->id_awards,
            $this->id_group_contracts,
            $this->id_enquiryPeriod,
            $this->id_tenderPeriod,
            $this->id_auctionPeriod,
            $this->auctionUrl,
            $this->id_awardPeriod,
            $this->status,
            $this->id_group_lots,
            $this->id_group_cancellations,
            $this->id_group_funders,
            $this->id_group_revisions
        ]);
        return DataBase::handler()->lastInsertId();
    }
    
    public static function statusTender(){ // В ідеалі створити ще одну таблицю в БД 
        $status = [
            "active.enquiries" => "Уточнення",
            "active.tendering" => "Пропозиції",
            "active.auction" => "Аукціон",
            "active.qualification" => "Кваліфікація",
            "active.awarded" => "Розглянуто",
            "unsuccessful" => "Завершена",
            "complete" => "Уточнення",
            "cancelled" => "Відмінена"
        ];
        return $status;
    }
    public static function getAllTender($start, $per_page){
        $st = DataBase::handler()->query("SELECT * FROM api_tender "
            . "LEFT JOIN api_ProcuringEntity ON (api_tender.id_procuringEntity = api_ProcuringEntity.id) "
            . "LEFT JOIN api_address ON (api_ProcuringEntity.id_address = api_address.id) "
            . "LEFT JOIN api_value ON (api_tender.id_value = api_value.id) "
            . "LIMIT $start,$per_page");
        $stmp['result'] = $st->fetchAll();
        $st_all = DataBase::handler()->query("SELECT * FROM api_tender");
        $stmp['count_all']= $st_all->rowCount();
        return $stmp;
    }
    public static function getTender($id){
        $st = DataBase::handler()->query("SELECT * FROM api_tender WHERE id='$id'");
        $stmp = $st->fetchAll();
        return $stmp;
    }
    public static function pagination($page_number){
        $st = DataBase::handler()->query("SELECT COUNT(*) as count FROM api_tender");
        $total_rows = $st->fetch();
        $num_pages = ceil( $total_rows['count']/50);
        return $num_pages;
    }
    public static function SearchTender($title, $edrpou, $classifier, $start, $per_page){
       // $st_count = DataBase::handler()->query("SELECT * FROM api_tender WHERE title LIKE '%$title%' ");
        $filds= "api_tender.id, api_tender.title, api_tender.status, api_tender.description, api_tender.tenderID, "
            . "api_tender.id_procuringEntity, api_tender.id_value, api_tender.date, api_tender.id_items, "
            . "api_ProcuringEntity.id as id_pe, api_ProcuringEntity.name, "
            . "api_ProcuringEntity.id_identifier, api_ProcuringEntity.id_address, "
            . "api_identifier.id as id_identif, "
            . "api_classification.id as id_classif, "
            . "api_value.id as id_val, api_value.amount, api_value.currency";
        if($edrpou){
            $where = "AND api_identifier.id_api = '$edrpou' ";
        }
        if($classifier){
            $where .= "AND api_classification.id_api = '$classifier' ";
        }
        $st = DataBase::handler()->query("SELECT $filds FROM api_tender "
            . "LEFT JOIN api_ProcuringEntity ON (api_tender.id_procuringEntity = api_ProcuringEntity.id) "
            . "LEFT JOIN api_address ON (api_ProcuringEntity.id_address = api_address.id) "
            . "LEFT JOIN api_value ON (api_tender.id_value = api_value.id) "
            . "LEFT JOIN api_identifier ON (api_ProcuringEntity.id_identifier = api_identifier.id) "
            . "LEFT JOIN api_item ON (api_tender.id_items = api_item.id) "
            . "LEFT JOIN api_classification ON (api_item.classification = api_classification.id) "
            . "WHERE api_tender.title LIKE '%$title%'  $where");
        $stmp['result'] = $st->fetchAll();
        $stmp['count_result'] = $st->rowCount();
       // $stmp['count_pagination'] = ceil($stmp['count_result']/50);
        return $stmp;
    }
}
