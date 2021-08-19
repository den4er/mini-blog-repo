<?php

/*
 *
 *
 * Модель для работы с новостями
 *
 *
 * */

class News
{
    // получение списка новостей
    public static function getNewsList()
    {

        $db = Db::getConnection(); // вызываем метод подключения к бд, тем самым создаем объект подключения

        $newsList = array(); // объявляем массив для результатов

        // выполняем запрос на получение данных и кладем в переменную
        $result = $db->query("SELECT id, h1, short_content, content, date "
                                        ."FROM news "
                                        ."ORDER BY date DESC "
                                        ."LIMIT 10");

        // формируем из ресурса массив с данными из бд
        $i = 0;
        while($row = $result->fetch())
        {
            $newsList[$i]['id'] = $row['id'];
            $newsList[$i]['h1'] = $row['h1'];
            $newsList[$i]['short_content'] = $row['short_content'];
            $newsList[$i]['content'] = $row['content'];
            $newsList[$i]['date'] = $row['date'];
            $i++;
        }

        return $newsList;
    }


    // получение новости по идентифкатору
    public static function getNewsItemById( $id )
    {
        $id = intval($id); // приводим к числу

        $db = Db::getConnection(); // вызываем метод подключения к бд, тем самым создаем объект подключения

        // делаем запрос, получаем данные из БД и кладем в переменную
        $result = $db->query("SELECT id, h1, short_content, content, date "
                                        . "FROM news "
                                        . "WHERE id=" . $id);

        $newsItem = $result->fetch(PDO::FETCH_ASSOC); // т к в ресурсе одна строка, просто кладем ее в переменную

        return $newsItem;

    }

    public static function getNewsListByCategory( $categoryId )
    {
        $db = Db::getConnection(); // вызываем метод подключения к бд, тем самым создаем объект подключения
        $newsList = array();

        // делаем запрос о категории, получаем данные из БД и кладем в переменную
        $result = $db->query("SELECT id, h1, short_content, content, date "
            . "FROM news "
            . "WHERE category_id=" . $categoryId);

        // формируем из ресурса массив с данными из бд
        $i = 0;
        while( $row = $result->fetch() )
        {
            $newsList[$i]['id'] = $row['id'];
            $newsList[$i]['h1'] = $row['h1'];
            $newsList[$i]['short_content'] = $row['short_content'];
            $newsList[$i]['content'] = $row['content'];
            $newsList[$i]['date'] = $row['date'];
            $i++;
        }

        return $newsList;

    }

}