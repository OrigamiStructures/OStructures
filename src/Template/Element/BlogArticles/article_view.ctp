<?php
use Sluggable\Utility\Slug;

list($slug, $return, $heading) = [
	Slug::generate($article->title),
	Slug::generate('toc-:title', $article), 
	$this->Html->tag('h1', $article->title, ['class' => 'article-title', 'itemprop' => 'headline'])];

?>
<article itemscope itemtype="http://schema.org/TechArticle">
	<?= $this->element('BlogArticles/article_toc'); ?>
	<?= sprintf(TOC_LINKBACK, $slug, $return, $heading); ?>
    <section itemprop="articleBody">
		<?= $this->CakeMarkdown->transform($article);?>
	</section>
	<section class="information">
		<?= $this->element('BlogArticles/publication_info'); ?>
	</section>
</article>
