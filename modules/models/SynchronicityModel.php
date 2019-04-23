<?php
class SynchronicityModel 
{
    public static function SynchronicityTenders($count){
        self::DeleteAllTenders();
        if(empty($count)){
            $count = 1;
        }
        session_start();
        $_SESSION['stop_process'] = false;
        $time_sec = time()+30;
         $tenders = file_get_contents( 
        "https://api.openprocurement.org/api/2.5/tenders?opt_pretty=1&descending=1&limit=$count" );
        $encode = json_decode($tenders, true);
        if(!empty($encode)){
            foreach($encode['data'] as $key=>$item){
                if($_SESSION['stop_process']){
                    echo "<p style='color:red; font-size: 18px;'>Зупинено!</p>";
                    die;
                }
                if(time() > $time_sec){
                    $result_ajax['timeout'] = 'true';
                    $result_ajax['key'] = $key;
                    $result_ajax['array'] = $encode['data'];
                    $result_ajax['count'] = $count;
                    echo json_encode($result_ajax);
                    die;
                }
                $id_tender = $item['id'];
                $tender= file_get_contents( "https://api.openprocurement.org/api/2.5/tenders/$id_tender" );
                $encode_new = json_decode($tender, true);
                if(!empty($encode_new)){
                    session_start();
                    $count_z = $count - ($key+1);
                    if(0 > $count_z){
                        $_SESSION['status_progress']['percent'] = round(($count - 1) / $count * 100);
                        $_SESSION['status_progress']['count'] = 'Залишилось синхронізувати - 1';
                    }else{
                        $_SESSION['status_progress']['percent'] = round(($count - $count_z) / $count * 100);
                        $_SESSION['status_progress']['count'] = 'Залишилось синхронізувати - '.$count_z;
                    }				
                    session_write_close();
                    self::AddTender($encode_new);
                }
            }
        }
    }
    public static function SynchronicityTimeoutTenders($array){
        $count = $array['count'];
        $time_sec = time()+30;
        foreach($array['array'] as $key=>$item){

            if($array['key'] > $key){
                continue;
            }
            if($_SESSION['stop_process']){
                echo "<p style='color:red; font-size: 18px;'>Зупинено!</p>";
                die;
            }
            if(time() > $time_sec){
                $result_ajax['timeout'] = 'true';
                $result_ajax['key'] = $key;
                $result_ajax['array'] = $array['array'];
                $result_ajax['count'] = $count;
                echo json_encode($result_ajax);
                die;
            }
            $id_tender = $item['id'];
            $tender= file_get_contents( "https://api.openprocurement.org/api/2.5/tenders/$id_tender" );
            $encode_new = json_decode($tender, true);
            if(!empty($encode_new)){
                session_start();
                    $count_z = $count - ($key+1);
                    if(0 > $count_z){
                        $_SESSION['status_progress']['percent'] = round(($count - 1) / $count * 100);
                        $_SESSION['status_progress']['count'] = 'Залишилось синхронізувати - 1';
                    }else{
                        $_SESSION['status_progress']['percent'] = round(($count - $count_z) / $count * 100);
                        $_SESSION['status_progress']['count'] = 'Залишилось синхронізувати - '.$count_z;
                    }				
                session_write_close();
                self::AddTender($encode_new);
            }
        }
    }
    public static function AddTender($tender){
        $tender = $tender['data'];
        
    // Documents
        if(!empty($tender["documents"])){
            $id_group_document = DocumentGroup::createDocumentGroup("TenderID-".$tender['id']); //Table Tender
            foreach ($tender["documents"] as $item){
                $obj_document = new Document();
                $obj_document->format = $item["format"];
                $obj_document->url = $item["url"];
                $obj_document->title = $item["title"];
                $obj_document->documentOf = $item["documentOf"];
                $obj_document->datePublished =  strtotime($item["datePublished"]);
                $obj_document->documentType = $item["documentType"];
                $obj_document->dateModified =  strtotime($item["dateModified"]);
                $obj_document->id_api = $item["id"];
                $obj_document->description = $item["description"];
                $obj_document->language = $item["title_en"];
                $obj_document->relatedItem = $item["id"];
                $obj_document->id_documents_group = $id_group_document;
                $obj_document->addDocument();
            }
        }
        
    //ProcuringEntity -> contactPoint
        if(!empty($tender["procuringEntity"]["contactPoint"])){
            $contactPoint = $tender["procuringEntity"]["contactPoint"];
            $obj_contact_point = new ContactPoint();
            $obj_contact_point->name = $contactPoint['name'];
            $obj_contact_point->email = $contactPoint['email'];
            $obj_contact_point->telephone = $contactPoint['telephone'];
            $obj_contact_point->faxNumber = $contactPoint['faxNumber'];
            $obj_contact_point->url = $contactPoint['url'];
            $id_contact_point = $obj_contact_point->addContact(); //Table api_ProcuringEntity
        }
    //ProcuringEntity -> identifier
        if(!empty($tender["procuringEntity"]["identifier"])){
            $identifier = $tender["procuringEntity"]["identifier"];
            $obj_identifier = new Identifier();
            $obj_identifier->scheme = $identifier['scheme'];
            $obj_identifier->id_api = $identifier['id'];
            $obj_identifier->legalName = $identifier['legalName'];
            $obj_identifier->uri = $identifier['uri'];
            $id_identifier = $obj_identifier->addIdentifier(); //Table api_ProcuringEntity
        }
    //ProcuringEntity -> address
        if(!empty($tender["procuringEntity"]["address"])){
            $address = $tender["procuringEntity"]["address"];
            $obj_address = new Address();
            $obj_address->streetAddress = $address['streetAddress'];
            $obj_address->locality = $address['locality'];
            $obj_address->region = $address['locality'];
            $obj_address->postalCode = $address['postalCode'];
            $obj_address->countryName = $address['countryName'];
            $id_address = $obj_address->addAddress();
        }
    //ProcuringEntity
            $obj_procuring = new ProcuringEntity();
            $obj_procuring->name = $tender['procuringEntity']['name'];
            $obj_procuring->id_identifier = $id_identifier;
            //$obj_procuring->id_additionalIdentifiers = '';
            $obj_procuring->id_address = $id_address;
            $obj_procuring->id_contactPoint = $id_contact_point;
            $obj_procuring->king = $tender['procuringEntity']['kind'];
            $id_procuring = $obj_procuring->addProcuring(); //Table Tender
    //Value
        if(!empty($tender["value"])){
            $obj_value = new Value();
            $obj_value->amount = $tender["value"]["amount"];
            $obj_value->currency = $tender["value"]["currency"];
            $obj_value->valueAddedTaxIncluded = $tender["value"]["valueAddedTaxIncluded"];
            $id_value = $obj_value->addValue(); //Table Tender
        }
    //Items -> Unit 
        if(!empty($tender['items'][0]['unit'])){
            $unit = $tender['items'][0]['unit'];
            $obj_unit = new Unit();
            $obj_unit->code = $unit['code'];
            $obj_unit->name = $unit['name'];
            $id_unit = $obj_unit->addUnit();
        }
    //Items -> deliveryDate     
        if(!empty($tender['items'][0]['deliveryDate'])){
            $deliveryDate = $tender['items'][0]['deliveryDate'];
            $obj_deliveryDate = new Period();
            $obj_deliveryDate->startDate = isset($deliveryDate['startDate']) ? strtotime($deliveryDate['startDate']) : 0; 
            $obj_deliveryDate->endDate = isset($deliveryDate['endDate']) ? strtotime($deliveryDate['endDate']) : 0;       
            $id_deliveryDate = $obj_deliveryDate->addPeriod();
        }
    //Items -> deliveryAddress
        if(!empty($tender['items'][0]['deliveryAddress'])){
            $deliveryAddress = $tender['items'][0]['deliveryAddress'];
            $obj_deliveryAddress = new Address();
            $obj_deliveryAddress->streetAddress = $deliveryAddress['streetAddress'];
            $obj_deliveryAddress->locality = $deliveryAddress['locality'];
            $obj_deliveryAddress->region = $deliveryAddress['locality'];
            $obj_deliveryAddress->postalCode = $deliveryAddress['postalCode'];
            $obj_deliveryAddress->countryName = $deliveryAddress['countryName'];
            $id_deliveryAddress = $obj_address->addAddress();
        }
    //Items -> Classification
        if(!empty($tender['items'][0]['classification'])){
            $classification = $tender['items'][0]['classification'];
            $obj_classification = new Classification();
            $obj_classification->scheme = $classification['scheme'];
            $obj_classification->id_api = $classification['id'];
            $obj_classification->description = $classification['description'];
            $obj_classification->uri = $classification['uri'];
            $id_classification = $obj_classification->addClassification();
        }    
    //Items 
        if($id_classification){
            $items = $tender['items'][0];
            $obj_items = new Items();
            $obj_items->id_api = $items['id'];
            $obj_items->description = $items['description'];
            $obj_items->classification = $id_classification;
           // $this->id_additionalClassifications =
            $obj_items->id_unit = $id_unit;
            $obj_items->quantity = $items['quantity'];
            $obj_items->id_deliveryDate = $id_deliveryDate;
            $obj_items->id_deliveryAddress = $id_deliveryAddress;
            //$this->id_deliveryLocation =
            //$this->id_relatedLot = 
            $id_items = $obj_items->addItems();
        } 
        
        
            //Final Table Tender
            
            $obj_tender = new Tender();
            $obj_tender->title = $tender['title'];
            $obj_tender->description = $tender['description'];
            $obj_tender->tenderID = $tender['tenderID'];
            $obj_tender->id_procuringEntity = $id_procuring;
            $obj_tender->id_value = $id_value;
            //$obj_tender->id_guarantee,
            $obj_tender->date = strtotime($tender['date']);
            $obj_tender->id_items = $id_items;
           // $obj_tender->id_features,
            $obj_tender->id_group_documents = $id_group_document;
           // $obj_tender->id_questions,
            //$obj_tender->id_complaints,
            //$obj_tender->id_bids,
           // $obj_tender->id_value_minimalStep,
            //$obj_tender->id_awards,
            //$obj_tender->id_group_contracts,
            //$obj_tender->id_enquiryPeriod,
           // $obj_tender->id_tenderPeriod,
           // $obj_tender->id_auctionPeriod,
            $obj_tender->auctionUrl = $tender['owner'];
            //$obj_tender->id_awardPeriod,
            $obj_tender->status = $tender['status'];
           // $obj_tender->id_group_lots
            //$obj_tender->id_group_cancellations,
            //$obj_tender->id_group_funders,
            //$obj_tender->id_group_revisions
            $obj_tender->addTender();
    }
    public static function DeleteAllTenders(){
        try{
            DataBase::handler()->beginTransaction();
            DataBase::handler()->exec("DELETE FROM api_tender");
            DataBase::handler()->exec("ALTER TABLE api_tender AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_award");
            DataBase::handler()->exec("ALTER TABLE api_award AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_bid");
            DataBase::handler()->exec("ALTER TABLE api_bid AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_cancellation");
            DataBase::handler()->exec("ALTER TABLE api_cancellation AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_classification");
            DataBase::handler()->exec("ALTER TABLE api_classification AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_complaint");
            DataBase::handler()->exec("ALTER TABLE api_complaint AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_contactPoint");
            DataBase::handler()->exec("ALTER TABLE api_contactPoint AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_contracts");
            DataBase::handler()->exec("ALTER TABLE api_contracts AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_document");
            DataBase::handler()->exec("ALTER TABLE api_document AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_feature");
            DataBase::handler()->exec("ALTER TABLE api_feature AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_guarantee");
            DataBase::handler()->exec("ALTER TABLE api_guarantee AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_identifier");
            DataBase::handler()->exec("ALTER TABLE api_identifier AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_item");
            DataBase::handler()->exec("ALTER TABLE api_item AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_lot");
            DataBase::handler()->exec("ALTER TABLE api_lot AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_LotValue");
            DataBase::handler()->exec("ALTER TABLE api_LotValue AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_organization");
            DataBase::handler()->exec("ALTER TABLE api_organization AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_parameter");
            DataBase::handler()->exec("ALTER TABLE api_parameter AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_period");
            DataBase::handler()->exec("ALTER TABLE api_period AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_ProcuringEntity");
            DataBase::handler()->exec("ALTER TABLE api_ProcuringEntity AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_question");
            DataBase::handler()->exec("ALTER TABLE api_question AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_revision");
            DataBase::handler()->exec("ALTER TABLE api_revision AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_unit");
            DataBase::handler()->exec("ALTER TABLE api_unit AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM api_value");
            DataBase::handler()->exec("ALTER TABLE api_value AUTO_INCREMENT = 1");
            
            DataBase::handler()->exec("DELETE FROM document_group");
            DataBase::handler()->exec("ALTER TABLE document_group AUTO_INCREMENT = 1");
            
            DataBase::handler()->commit();
        } catch (PDOException $e){
            DataBase::handler()->rollBack();
            echo $e->getMessage();
        }
    }
}
