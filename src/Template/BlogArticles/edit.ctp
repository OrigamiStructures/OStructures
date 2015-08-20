<?php
$this->append('css');
    echo $this->Html->css('article-authoring');
$this->end();
$this->append('script');
    echo $this->Html->script('article-authoring');
$this->end();
?>
<div class="row sm-tall-100">
	<div class="resource-zone">
		<?= $this->element('BlogArticles/resources', [
			'articleImages' => $articleImages,
			'linkedImages' => $linkedImages,
			'unlinkedImages' => $unlinkedImages,
			'otherArticles' => $otherArticles]); ?>
	</div>
	<div class="edit-zone">
		<?= $this->Form->create($article); ?>
		<?= $this->element('BlogArticles/edit_fieldset') ?>
		<?= $this->Form->input('continue', ['label' => 'Preview and continue editing', 'type' => 'checkbox']); ?>
		<?= $this->Form->button(__('Submit'), ['class' => 'button expand']); ?>
		<?= $this->Form->end(); ?>
	</div>
	<div class="preview-zone">
		<?= $this->element('BlogArticles/article_view') ?>
	</div>
</div>
