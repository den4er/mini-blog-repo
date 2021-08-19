<?php

// FRONT CONTROLLER

// 1. Общие настройки

    ini_set('display_errors', 1); // включаем отображение ошибок
    error_reporting(E_ALL);         // отображать все ошибки

// 2. Подключение файлов системы

    define( 'ROOT', dirname(__FiLE__)); // определяем константу корневой папки
    require_once(ROOT.'/components/Router.php'); // подключаем клас роутер (Router.php)
    require_once(ROOT.'/components/Db.php'); // подключаем класс подключения к бд


// 3. Установка соединения с БД

    // connect db
//    try {
//        $dbh = new PDO('mysql:host=localhost;dbname=testsite', 'root', '');
//    } catch (PDOException $e) {
//        print "Error!: " . $e->getMessage() . "<br/>";
//        die();
//    }

// 4. Вызов Router

    $router = new Router();
    $router->run();

