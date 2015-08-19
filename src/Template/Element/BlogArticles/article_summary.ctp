<article
    itemscope 
    itemtype="http://schema.org/TechArticle"
>
    <h1 class="article-title"><?= $article->title; ?></h1>
    <?= $this->CakeMarkdown->transform($article->summary);?>
    <?= $this->element('Authors/author_view', ['authors' => $article->authors]); ?>
</article>
