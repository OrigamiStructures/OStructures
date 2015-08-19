<?php
use Sluggable\Utility\Slug;

list($slug, $return, $heading) = [
	Slug::generate($article->title),
	Slug::generate('toc-:title', $article), 
	$this->Html->tag('h1', $article->title, ['class' => 'article-title'])];

?>
<article>
	<?= $this->element('BlogArticles/article_toc'); ?>
	<?= sprintf(TOC_LINKBACK, $slug, $return, $heading); ?>
    <?= $this->CakeMarkdown->transform($article);?>
</article>
