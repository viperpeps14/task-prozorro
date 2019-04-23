<?php
class DocumentGroup {
    public static function createDocumentGroup($name){
        $sql = "INSERT INTO document_group("
                . "name)"
                . "VALUES(?);";
        $stmt = DataBase::handler()->prepare($sql);
        $stmt->execute([
            $name
        ]);
        return DataBase::handler()->lastInsertId();
    } 
}
