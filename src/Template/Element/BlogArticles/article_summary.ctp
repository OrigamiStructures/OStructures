<article itemprop="itemListElement" itemscope="" itemtype="http://schema.org/TechArticle">
    <h1 class="article-title" itemprop="headline">
        <a itemprop="url" href="<?=$this->request->webroot;?>article/<?=$article->slug; ?>">
            <?= $article->title; ?>
        </a>
    </h1>
    <?= $this->Html->link('Edit', ['action' => 'edit', $article->id]); ?>
    <p>Last Updated: <span  itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span></p>
    <section itemprop="description">
        <?= $this->Markdown->transform($article->summary);?>
    </section>
</article>
