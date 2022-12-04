<?php
/**
 * Class News - модель для получения данных о новостях
 */
class News
{

  /**
   * получение одной новости по ID
   */
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

  /**
   * получение ограниченного по числу списка новостей
   */
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

  /**
   *  получение списка новостей без категорий
   */
  public static function getNewsList(){
    $pdo = DB::getConnection();
    $query = "SELECT news.id as news_id, title, text, add_date, image, 
                authors.id as author_id, first_name, last_name, avatar 
                FROM news, authors 
                WHERE author_id = authors.id 
                ORDER BY add_date DESC;";
    return $pdo->query($query)->fetchAll();
  }

  /**
   * добавление категорий новостей в выборку новостей
   */
  public static function addCategory($newsList){
    $pdo = DB::getConnection(); // подключение к бд
    // запрос на выборку категорий
    $query = "SELECT category_id, title, translation, description, class_name
              FROM news_category, category 
              WHERE category_id = category.id AND news_id=?;";
    $result = $pdo->prepare($query);

    // перебираем новости и получаем у каждой новости категории, к которым новость принадлежит
    foreach ($newsList as $key => $newsItem) {
      $result->execute([$newsItem['news_id']]);
      $categories = $result->fetchAll();

      // добавляем в каждую новость данные о категориях этой новости
      foreach ($categories as $category) {
        $newsItem['categories'][] = ['title' => $category['title'], 'translation' => $category['translation'],  'class_name' =>
          $category['class_name']];
      }
      $newsList[$key] = $newsItem;
    }

    //Debug::d($newsList);
    return $newsList;
  }

  /**
   * получение новостей со списком категорий
   */
  public static function getCategoryNews(){
    $newsList = self::getNewsList(); // получение списка новостей
    return self::addCategory($newsList); // добавляем категории и возвращаем итоговый список новостей
  }

  /**
   * получение списка категорий
   */
  public function getCategories(){
    $pdo = DB::getConnection(); // подключение к бд

    $query = "SELECT id, title, translation, description, class_name FROM category";
    return $pdo->query($query)->fetchAll();
  }

  /**
   * получение данных по одной категорий по title
   */
  public static function getCategoryByTitle($title){
    $pdo = DB::getConnection(); // подключение к бд

    $query = "SELECT id, title, translation, description, class_name 
              FROM category
              WHERE title = ?";
    $stm = $pdo->prepare($query);
    $stm->execute([$title]);
    return $stm->fetch();
  }

  /**
   * получение списка новостей по выбранной категории
   */
  public static function getNewsListByCategory($category){
    $pdo = DB::getConnection(); // подключение к бд
    $query = "SELECT category.id as category_id, category.title as category_title, translation, description, class_name,
                news.id as news_id, news.title, text, add_date, image, 
                authors.id as author_id, first_name, last_name, avatar
                FROM category, news_category, news, authors
                WHERE category.id = category_id AND
                      news_id = news.id AND
                      author_id = authors.id AND
                      category.title = ?;";
    $stm = $pdo->prepare($query);
    $stm->execute([$category]);

    $newsList = $stm->fetchAll();
    return self::addCategory($newsList); // добавляем категории и возвращаем итоговый список новостей
  }

  /**
   * получение списка категорий с количеством новостей в каждой категории
   */
  public static function countNewsByCategories(){
    $pdo = DB::getConnection(); // подключение к бд

    $query = "SELECT category_id, title, translation, COUNT(*) as count
              FROM news_category, category 
              WHERE category.id = category_id 
              GROUP BY category_id;";

    return $pdo->query($query)->fetchAll();

  }

  /**
   * получение списка комментариев конкретной новости
   */
  public static function getCommentsByNewsId($id){
    $pdo = DB::getConnection(); // подключение к бд

    // пока статический массив, реализовать выборку из бд по id новости
    $comments = [
      ['id' => '1', 'parent_id' => '0', 'text' => 'первый комментарий'],
      ['id' => '2', 'parent_id' => '0', 'text' => 'второй комментарий' ],
      ['id' => '3', 'parent_id' => '0', 'text' => 'третий комментарий'],
      ['id' => '4', 'parent_id' => '2', 'text' => 'первый ответ'],
      ['id' => '5', 'parent_id' => '0', 'text' => 'четвертый комментарий'],
      ['id' => '6', 'parent_id' => '0', 'text' => 'пятый комментарий'],
      ['id' => '7', 'parent_id' => '2', 'text' => 'второй ответ'],
      ['id' => '8', 'parent_id' => '4', 'text' => 'третий ответ'],
      ['id' => '9', 'parent_id' => '0', 'text' => 'шестой комментарий'],
    ];
    $res = [];

    foreach($comments as $key => $value) {
      $res[$value['parent_id']][] = [ $value['id'], $value['text'] ];
    }
    return $res;

  }

}
