<article>
    <?php
//    debug($article);
    ?>
    <h2 class="article-title"><?= $article->title; ?></h2>
    <?= $this->CakeMarkdown->transform($article->text);?>
</article>
