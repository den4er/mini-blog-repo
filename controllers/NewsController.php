<?php

include_once( ROOT . '/models/News.php');


class NewsController
{
    // просмотр списка новостей
    public function actionIndex()
    {

        $news_list = array();
        $news_list = News::getNewsList();

        require_once(ROOT . '/views/news/index.php');

        return true;
    }

    // просмотр одной новости по ID
    public function actionDetail($id)
    {
        if($id)
        {
            $id = $id[0];

            $newsItem = News::getNewsItemById( $id );

            require_once(ROOT . '/views/news/view.php');

        }

        return true;
    }

    // просмотр новостей в категории
    public function actionCategoryList( $category )
    {
        $category = $category[0];


        switch ( $category ) {
            case 'football':
                $categoryId = 1;
                break;
            case 'formulaone':
                $categoryId = 2;
                break;
            case 'basketball':
                $categoryId = 3;
                break;
            case 'tennis':
                $categoryId = 4;
                break;
            case 'hockey':
                $categoryId = 5;
                break;
            default:
                $categoryId = 1;
        }


        $news_list = array();
        $news_list = News::getNewsListByCategory( $categoryId );

        require_once(ROOT . '/views/news/index.php');

        return true;
    }
}