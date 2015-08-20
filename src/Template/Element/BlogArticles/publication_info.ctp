<p class="published">
    Last updated: <span itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span> 
	| Originally published: <?= $this->Time->format($article->published, 'yyyy-MM-dd'); ?>
</p>
<p class="copyright">Copyright 
	<span itemprop="copyrightYear"><?= $this->Time->format($article->modified, 'yyyy'); ?></span>, 
	<span itemprop="copyrightHolder" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">Origami Structures</span> 
	(<a itemprop="url" href="http://origamistructures.com">origamistructures.com</a>)</span>
</p>