<?php
require 'views/components/header.php';
?>


<div class="site-section bg-white">
    <div class="container">

        <div class="row mb-5">
            <div class="col-12">
                <h2>Все новости</h2>
            </div>
        </div>

        <div class="row">

          <?php foreach ( $newsList as $key => $newsItem) :?>
            <div class="col-lg-4 mb-4">
                <div class="entry2">
                    <a href="/news/<?=$newsItem['news_id']?>"><img src="<?=$newsItem['image']?>" alt="Image" class="img-fluid rounded"></a>
                    <div class="excerpt">

                        <?php foreach ( $newsItem['categories'] as $key => $category) :?>
                            <span class="post-category text-white <?=$category['class_name']?>
                            mb-3"><?=$category['title']?></span>
                        <?php endforeach;?>

                        <h2><a href="/news/<?=$newsItem['news_id']?>"><?=$newsItem['title']?></a></h2>
                        <div class="post-meta align-items-center text-left clearfix">
                            <figure class="author-figure mb-0 mr-3 float-left"><img src="<?=$newsItem['avatar']?>" alt="<?=$newsItem['first_name']?> <?=$newsItem['last_name']?>" class="img-fluid"></figure>
                            <span class="d-inline-block mt-1"><a href="#"><?=$newsItem['first_name']?> <?=$newsItem['last_name']?></a></span>
                            <span>&nbsp;-&nbsp; <?=$newsItem['add_date']?></span>
                        </div>

                        <p><?=mb_substr($newsItem['text'], 0, 150) . '...'?></p>
                        <p><a href="/news/<?=$newsItem['news_id']?>">Read More</a></p>
                    </div>
                </div>
            </div>
          <?php endforeach;?>

        </div>

        <!--   пагинация     -->
        <div class="row text-center pt-5 border-top">
            <div class="col-md-12">
                <div class="custom-pagination">
                    <span>1</span>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <span>...</span>
                    <a href="#">15</a>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
require 'views/components/footer.php';
?>


