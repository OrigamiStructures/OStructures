<section class="publication_details row">
    <p class="published">
        <?= $this->Article->editLink($article); ?>
        Last updated: <span itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span> 
        | Originally published: <?= $this->Time->format($article->created, 'yyyy-MM-dd'); ?>
    </p>
    <?= $this->element('Common/copyright', ['entity' => $article, 'tag' => 'p']) ?>
</section>
