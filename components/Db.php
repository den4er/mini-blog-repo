<?php

/*
 *
 *
 * Класс для получения объекта подключения к БД
 *
 *
 * */
class Db
{
    public static function getConnection()
    {
        $paramsPath = ROOT . '/config/db_params.php'; // получаем путь до параметров подключения
        $params = include($paramsPath); // подключаем файл с параметрами и записываем их в переменную

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']};charset=UTF8"; // создаем dsn для подключения
        $db = new PDO( $dsn, $params['user'], $params['password'] ); // создаем объект подключения к бд

        return $db;
    }
}