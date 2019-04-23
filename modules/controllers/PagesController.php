<?php
class PagesController extends Controller 
{
    public function indexAction()
    {
        $tenders['tenders'] = array('t'=>'t');
        $content = View::getContents(ROOT . "/modules/views/pages/index.php", $tenders);
        $this->mainView->addParam("content", $content);
        $this->mainView->display();
    }
    public function synchronicityAction(){
        $tenders['tenders'] = array('t'=>'t');
        $content = View::getContents(ROOT . "/modules/views/pages/synchronicity.php", $tenders);
        $this->mainView->addParam("content", $content);
        $this->mainView->display();
    }
    public function tendersAction(){
        
        $per_page=50;
        // получаем номер страницы
        if (isset($_GET['page'])) {
           $page=$_GET['page']-1;
        }else{
            $page=0;
        } 
        // вычисляем первый оператор для LIMIT
        $start=abs($page*$per_page);
        $tenders['status'] = Tender::statusTender();
        $tenders['tenders'] = Tender::getAllTender($start, $per_page);
        $tenders['count'] = Tender::pagination($_GET['page']);
        $tenders['page'] = $_GET['page'];
        $content = View::getContents(ROOT . "/modules/views/pages/tenders.php", $tenders);
        $this->mainView->addParam("content", $content);
        $this->mainView->display();
    }
    public function searchAction(){
        $title = $_GET['title'];
        $edrpou = $_GET['EDRPOU'];
        $classifier = $_GET['classifier'];
        $tenders['status'] = Tender::statusTender();
        $tenders['tenders'] = Tender::SearchTender($title, $edrpou, $classifier, $start, $per_page);
        $tenders['page'] = $_GET['page'];
        $content = View::getContents(ROOT . "/modules/views/pages/search.php", $tenders);
        $this->mainView->addParam("content", $content);
        $this->mainView->display();
    }
     public function tenderAction($id){
        $tender['tender'] = Tender::getTender($id[0]);
        $content = View::getContents(ROOT . "/modules/views/pages/tender.php", $tender);
        $this->mainView->addParam("content", $content);
        $this->mainView->display();
    }
}
