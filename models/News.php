<?php
/**
 * Class News - модель для получения данных о новостях
 */
// 644 * 429
class News
{
  // получение одной новости по ID
  public static function getNewsItemById($id){
    $id = (int)$id;
    if($id){
      $pdo = DB::getConnection();

      // получаем новость и автора
      $query = "SELECT news.id as news_id, title, text, add_date, image, 
                authors.id as author_id, first_name, last_name, short_info, avatar 
                FROM news, authors 
                WHERE author_id = authors.id AND news.id = ?
                ORDER BY title;";
      $stm = $pdo->prepare($query);
      $stm->execute([$id]);
      $newsItem = $stm->fetch();

      $query = "SELECT news_id, category_id, title, description, class_name
                FROM news_category, category
                WHERE category_id = category.id AND news_id = ?";
      $stm = $pdo->prepare($query);
      $stm->execute([$id]);
      $newsItem['categories'] =  $stm->fetchAll();

      return $newsItem;
    }
  }

  // получение списка новостей без категорий
  public static function getNewsList(){
    $pdo = DB::getConnection();
    $query = "SELECT news.id as news_id, title, text, add_date, image, 
                authors.id as author_id, first_name, last_name, avatar 
                FROM news, authors 
                WHERE author_id = authors.id 
                ORDER BY title;";
    return $pdo->query($query)->fetchAll();
  }

  // получение ограниченного списка новостей
  public static function getPopularNews($limit){
    $pdo = DB::getConnection();
    $query = "SELECT id, title, add_date, image 
                FROM news
                ORDER BY rand() 
                LIMIT :limit;";
    $stm = $pdo->prepare($query);
    $stm->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
  }

  // получение новостей со списком категорий
  public static function getCategoryNews(){
    $pdo = DB::getConnection(); // подключение к бд
    $newsList = self::getNewsList(); // получение списка новостей

    // запрос на выборку категорий
    $query = "SELECT category_id, title, description, class_name
              FROM news_category, category 
              WHERE category_id = category.id AND news_id=?;";
    $result = $pdo->prepare($query);

    $newsListCat = []; // массив для списка новостей с категориями

    // перебираем новости и получаем у каждой новости категории
    foreach ($newsList as $newsItem) {
      $result->execute([$newsItem['news_id']]);
      $categories = $result->fetchAll();

      foreach ($categories as $category) {
        $newsItem['categories'][] = ['title' => $category['title'], 'class_name' => $category['class_name']];
      }
      $newsListCat[] = $newsItem;

    }
    return $newsListCat;
  }

}