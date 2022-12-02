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
      $newsItem = News::getNewsItemById($id); // получаем новость
      $newsItem['text'] = str_replace("\r\n\r\n", '</p><p>', $newsItem['text']);
      // получаем список категорий с подсчетом новостей по каждой из них
      $totalNewsByCategory = News::countNewsByCategories();
      // популярные посты
      $popularItems = News::getPopularNews(3);

      //Debug::d($totalNewsByCategory);

      require ROOT . '/views/news/view.php';
    }

    return true;
  }

  // выборка новостей по категории
  public function actionCategory($category){
    // получаем новости по нужной категории
    $newsList = News::getNewsListByCategory($category);
    $category = News::getCategoryByTitle($category);
    //Debug::d($category);

    require ROOT . '/views/news/index.php';

    return true;
  }
}