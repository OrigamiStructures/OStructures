<?php
use Sluggable\Utility\Slug;
/* @var \App\View\AppView $this */


list($slug, $return, $heading) = [
	Slug::generate($article->title),
	Slug::generate('toc-:title', $article),
	$this->Html->tag('h1', $article->title, ['class' => 'article-title', 'itemprop' => 'headline'])];

?>
<article itemscope itemtype="http://schema.org/TechArticle">
	<?= $this->element('BlogArticles/article_toc'); ?>
	<?= sprintf(TOC_LINKBACK, $slug, $return, $heading); ?>
    <section itemprop="articleBody" class="body">
		<?= $this->Markdown->transform($article);?>
	</section>
	<section id="<?= Slug::generate('info-:title', $article) ?>" class="info">
		<?= sprintf(TOC_LINKBACK, $slug, $return, ''); ?>
		<h1>Publication details</h1>
		<?= $this->element('BlogArticles/publication_info'); ?>
		<?= $this->element('Authors/author_view', ['authors' => $article->authors]); ?>
	</section>
</article>
