<?php

require ROOT . '/models/News.php';

// контроллер страницы с новостями
class NewsController
{

  // рекурсивный метод построения дерева комментариев
  private static function  makeTree($arr, $root = 0, $level = 0) {
    $ulClass = $level?'children':'comment-list';

    echo "<ul class='$ulClass'>";
      foreach($arr[$root] as $i) {
        echo "<li  class='comment' id='$i[0]'>";
          echo '<div class="vcard">';
              echo '<img src="/template/images/authors/rachel-green.jpg" alt="Image placeholder">';
          echo '</div>';
          echo '<div class="comment-body">';
            echo '<h3>Jean Doe</h3>';
            echo '<div class="meta">January 9, 2018 at 2:21pm</div>';
            echo '<p>'. $i[1] .'</p>';
            echo '<p><a href="#" class="reply rounded">Reply</a></p>';
          echo '</div>';
          if (isset($arr[$i[0]])) self::makeTree($arr, $i[0], ++$level);
        echo "</li>";

      }
    echo "</ul>";
  }

  // метод отображения списка новостей
  public function actionIndex(){

    $newsList = News::getCategoryNews();
    //Debug::d($newsList);

    require ROOT . '/views/news/index.php';

    return true;
  }

  // метод отображения одной новости детально
  public function actionView($id){
    if($id){
      $newsItem = News::getNewsItemById($id); // получаем новость
      $newsItem['text'] = str_replace("\r\n\r\n", '</p><p>', $newsItem['text']);

      // получаем список категорий с подсчетом новостей по каждой из них
      $totalNewsByCategory = News::countNewsByCategories();

      // популярные посты
      $popularItems = News::getPopularNews(3);

      // комментарии
      $comments = News::getCommentsByNewsId($id);// получаем комментарии


      //Debug::d($comments);

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
/*
// тест вывода комментариев к новости
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

makeTree($res);

function makeTree($arr, $root = 0) {
  echo "<ul>";
  foreach($arr[$root] as $i) {
    echo "<li id='$i[0]'>";
    echo $i[1];
    echo "<form method='POST'>";
    echo "<input type='hidden' name='comment_id' value='$i[0]'>";
    echo "<input type='submit' name='action' value='Ответить'>";
    echo "</form>";
    if (isset($arr[$i[0]])) MakeTree($arr, $i[0]);
    echo "</li>";

  }
  echo "</ul>";
}
*/