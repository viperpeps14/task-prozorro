<?php
class View 
{
    protected $File;
    protected $Params;

    public function __construct($file, $params=array()) 
    {
        $this->File = $file;
        $this->Params = $params;
    }
    
    public function addParam($name, $value) 
    {
        $this->Params[$name] = $value;
    }
    
    public function setTemplate($file) 
    {
        $this->File = $file;
    } 
    
    public function getContent()
    {
        extract($this->Params);
        ob_start();
        include($this->File);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    } 
    
    public function display() 
    {
        echo $this->getContent();
    }

    public static function getContents($file, $params = []) 
    {
        $view = new View($file, $params);
        return $view->getContent();
    }
    public function errorQuery()
    {
        $result_array = [];
        $content = self::getContents(ROOT . "/modules/views/error.tpl", $result_array); 
        $this->addParam("content", $content);
        $this->display();
    }
    public function errorAccess()
    {
        $result_array = [];
        $content = self::getContents(ROOT . "/modules/views/errorAccess.tpl", $result_array); 
        $this->addParam("content", $content);
        $this->display();
    }
}

   