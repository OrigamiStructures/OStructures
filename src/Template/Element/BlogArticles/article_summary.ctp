<article
    itemscope 
    itemtype="http://schema.org/TechArticle"
>
    <h1 class="article-title"><?= $article->title; ?></h1>
    <?= $this->Html->link('Edit', ['action' => 'edit', $article->id]); ?>
    <?= $this->Markdown->transform($article->summary);?>
</article>
