<?php
class Document {
    public $format;
    public $url;
    public $title;
    public $documentOf;
    public $datePublished;
    public $documentType;
    public $dateModified;
    public $id_api;
    public $description;
    public $language;
    public $relatedItem;
    
    public $id_documents_group;
    
    public function addDocument(){
        $sql = "INSERT INTO api_document("
            ."format,"
            ."url,"
            ."title,"
            ."documentOf,"
            ."datePublished,"
            ."documentType,"
            ."dateModified,"
            ."id_documents_group,"
            ."description,"
            ."language,"
            ."relatedItem,"
            ."id_api)"
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $this->format,
            $this->url,
            $this->title,
            $this->documentOf,
            $this->datePublished,
            $this->documentType,
            $this->dateModified,
            $this->id_documents_group,
            $this->description,
            $this->language,
            $this->relatedItem,
            $this->id_api
        ]);
        return DataBase::handler()->lastInsertId();
    }
}
