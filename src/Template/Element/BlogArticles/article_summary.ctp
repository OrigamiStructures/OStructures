<article>
    <h1 class="article-title"><?= $article->title; ?></h1>
    <?= $this->CakeMarkdown->transform($article->summary);?>
</article>
