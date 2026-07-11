<?php
    echo '<h1>Параметры Сервера</h1>';
    echo "Операционная система: " ;
  //  $_SERVER["OS"] . "<br />";
  echo "Браузер: " . 
    $_SERVER["HTTP_USER_AGENT"] . "<br />";
  echo "Хост: " . 
    $_SERVER["HTTP_HOST"] . "<br />";
  echo "Протокол: " . 
    $_SERVER["SERVER_PROTOCOL"] . "<br />";
    echo "Web-сервер: " . 
    $_SERVER["SERVER_SOFTWARE"] . "<br />";
    echo "Имя сервера: " . 
    $_SERVER["SERVER_NAME"] . "<br />";
    echo "Адрес сервера: " . 
    $_SERVER["SERVER_ADDR"] . "<br />";
    echo "Порт сервера: " . 
    $_SERVER["SERVER_PORT"] . "<br />";
    echo "Адрес клиента: " . 
    $_SERVER["REMOTE_ADDR"] . "<br />";
    echo "Путь к документам на сервере: " . 
    $_SERVER["DOCUMENT_ROOT"] . "<br />";
    echo "Полный путь к текущему скрипту: " . 
    $_SERVER["SCRIPT_FILENAME"] . "<br />";
    echo "Имя текущего скрипта: " . 
    $_SERVER["PHP_SELF"] . "<br />";
    echo "Переменная dirname(__FILE__)";
    echo dirname(__FILE__) . "<br />";
    echo "Переменная dirname(dirname(__FILE__))";
    echo dirname(dirname(__FILE__));

    echo phpinfo();
?>
 
