<section class="publication_details row">
    <p class="published">
		<?= ($this->request->env('HTTP_HOST')) === 'localhost' ? 
			$this->Html->link('Edit', ['action' => 'edit', $article->id]) . ' | ' : ''; ?>
        Last updated: <span itemprop="datePublished"><?= $this->Time->format($article->published, 'yyyy-MM-dd') ?></span> 
        | Originally published: <?= $this->Time->format($article->published, 'yyyy-MM-dd'); ?>
    </p>
    <?= $this->element('Common/copyright', ['entity' => $article, 'tag' => 'p']) ?>
</section>
