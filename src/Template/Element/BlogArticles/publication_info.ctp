<p class="published">
    Last updated: <span itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span> 
	| Originally published: <?= $this->Time->format($article->published, 'yyyy-MM-dd'); ?>
</p>
<?= $this->element('Common/copyright', ['entity' => $article, 'tag' => 'p']) ?>