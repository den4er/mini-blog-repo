<?php

require ROOT . '/models/News.php';

// контроллер страницы с новостями
class NewsController
{

  // метод отображения списка новостей
  public function actionIndex(){

    $newsList = News::getCategoryNews();
    //Debug::d($newsList);

    require ROOT . '/views/news/index.php';

    return true;
  }

  public function actionView($id){ // метод отображения одной новости детально
    if($id){
      // новость для основного списка
      $newsItem = News::getNewsItemById($id);
      $newsItem['text'] = str_replace("\r\n\r\n", '</p><p>', $newsItem['text']);
      //Debug::d($newsItem);

      // популярные посты
      $popularItems = News::getPopularNews(3);

      require ROOT . '/views/news/view.php';
    }

    return true;
  }

  // выборка новостей по категории
  public function actionCategory($category){
    $newsList = News::getNewsListByCategory($category);
    //Debug::d($newsList);

    require ROOT . '/views/news/index.php';

    return true;
  }
}