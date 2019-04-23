<?php
session_start();
    define("ROOT", __DIR__);
      include(ROOT . "/config/config.php");
    spl_autoload_register(function($className){
        $dirs = [
            ROOT . "/core/",
            ROOT . "/class/",
            ROOT . "/modules/controllers/",
            ROOT . "/modules/models/",
        ];
        foreach($dirs as $dir) {
            if (is_file($dir . $className . ".php")) {
                include $dir . $className . ".php";
            }
        }
    });
    DataBase::Connect();
    $controller = new Controller();
    $controller->run();