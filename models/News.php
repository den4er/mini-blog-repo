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
                WHERE author_id = authors.id AND news.id = ?;";
      $stm = $pdo->prepare($query);
      $stm->execute([$id]);
      $newsItem = $stm->fetch();

      // получаем категории новости
      $query = "SELECT news_id, category_id, title, translation, description, class_name
                FROM news_category, category
                WHERE category_id = category.id AND news_id = ?";
      $stm = $pdo->prepare($query);
      $stm->execute([$id]);
      $newsItem['categories'] = $stm->fetchAll();

      return $newsItem;
    }
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

  // получение списка новостей без категорий
  public static function getNewsList(){
    $pdo = DB::getConnection();
    $query = "SELECT news.id as news_id, title, text, add_date, image, 
                authors.id as author_id, first_name, last_name, avatar 
                FROM news, authors 
                WHERE author_id = authors.id 
                ORDER BY add_date DESC;";
    return $pdo->query($query)->fetchAll();
  }

  // добавление категорий новости в выборку новостей
  public static function addCategory($newsList){
    $pdo = DB::getConnection(); // подключение к бд
    // запрос на выборку категорий
    $query = "SELECT category_id, title, translation, description, class_name
              FROM news_category, category 
              WHERE category_id = category.id AND news_id=?;";
    $result = $pdo->prepare($query);

    $newsListCat = []; // массив для списка новостей с категориями

    // перебираем новости и получаем у каждой новости категории
    foreach ($newsList as $newsItem) {
      $result->execute([$newsItem['news_id']]);
      $categories = $result->fetchAll();

      // добавляем в массив с новостями категории каждой новости
      foreach ($categories as $category) {
        $newsItem['categories'][] = ['title' => $category['title'], 'translation' => $category['translation'],  'class_name' =>
          $category['class_name']];
      }
      $newsListCat[] = $newsItem;
    }
    return $newsListCat;
  }

  // получение новостей со списком категорий
  public static function getCategoryNews(){
    $newsList = self::getNewsList(); // получение списка новостей
    return self::addCategory($newsList); // добавляем категории и возвращаем итоговый список новостей
  }

  // получение списка категорий
  public function getCategories(){
    $pdo = DB::getConnection(); // подключение к бд

    $query = "SELECT id, title, translation, description, class_name FROM category";
    return $pdo->query($query)->fetchAll();
  }

  // получение списка новостей по указанной категории
  public static function getNewsListByCategory($category){
    $pdo = DB::getConnection(); // подключение к бд
    $query = "SELECT category.id as category_id, category.title as category_title, translation, class_name,
                news.id as news_id, news.title, text, add_date, image, 
                authors.id as author_id, first_name, last_name, avatar
                FROM category, news_category, news, authors
                WHERE category.id = category_id AND
                      news_id = news.id AND
                      author_id = authors.id AND
                      category.title = ?;";
    $stm = $pdo->prepare($query);
    $stm->execute([$category]);

    return $stm->fetchAll();
  }

}
// SELECT category.id, title, translation, news_id
// FROM category, news_category
// WHERE category.id = category_id AND title = sport;