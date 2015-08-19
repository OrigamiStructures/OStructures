<?php
?>

<?= $this->Html->tag('h2', 'Sidebar nav'); ?>
<ul id="recentArticleLinkList"></ul>
<?php foreach ($recent as $article) : ?>
	<li>
		<?= $this->Html->link($article->title, ['action' => 'article', $article->slug]) ?>
	</li>
<?php endforeach; ?>
</ul>

    <!--debug($data);-->
