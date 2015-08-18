<?php
use Sluggable\Utility\Slug;

list($slug, $return, $heading) = [
	Slug::generate($article->title),
	Slug::generate('toc-:title', $article), 
	$this->Html->tag('h1', $article->title, ['class' => 'article-title'])];

?>
<article>
	<?= sprintf(TOC_LINKBACK, $slug, $return, $heading); ?>
    <?= $this->CakeMarkdown->transform($article);?>
</article>
