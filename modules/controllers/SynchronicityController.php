<?php
class SynchronicityController extends Controller{
    public function handlerAction(){
       SynchronicityModel::SynchronicityTenders($_POST['count']);
    }
    public function stopAction(){
        session_start();
        $_SESSION['stop_process'] = true;
    }
    public function progressbarAction(){
        session_start();
        echo json_encode($_SESSION['status_progress']);
        exit();
    }
    public function timeoutAction(){
        $params = $_POST['params'];
        SynchronicityModel::SynchronicityTimeoutTenders($params);
    }
    public function deleteAction(){
        $params = $_POST['params'];
        SynchronicityModel::DeleteAllTenders($params);
    }
}
