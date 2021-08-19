<?php

include_once( ROOT . '/models/News.php');

class IndexController
{
    // главная страница
    public function actionIndex()
    {

        // делаем выборку новостей по категориям и кладем в переменные
        $footballNews = News::getNewsListByCategory(1);
        $formulaoneNews = News::getNewsListByCategory(2);
        $basketballNews = News::getNewsListByCategory(3);
        $tennisNews = News::getNewsListByCategory(4);
        $hockeyNews = News::getNewsListByCategory(5);

        require_once(ROOT . '/views/index.php');

        return true;
    }
}