<?php
?>
<!-- Common/sidebar -->
<?= $this->Html->tag('h1', 'Recent Articles'); ?>
<ul id="recentArticleLinkList">
<?php foreach ($recent as $article) : ?>
	<li>
		<?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
	</li>
<?php endforeach; ?>
</ul>
<?= $this->element('Common/topic_filters'); ?>
<!-- End Common/sidebar -->

