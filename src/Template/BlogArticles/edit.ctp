<?php
$this->append('css');
echo $this->Html->css('article-edit');
$this->end();
?>
<div class="row">
	<div class="small-12 medium-4 large-2 columns resource-zone">
		<p >Resources</p>
	</div>
	<div class="medium-8 large-5 columns edit-zone"">
		<?= $this->Form->create($article); ?>
		<?= $this->element('BlogArticles/edit_fieldset') ?>
		<?= $this->Form->button(__('Submit')); ?>
		<?= $this->Form->end(); ?>
	</div>
	<div class="large-5 columns preview-zone"">
		<?= $this->CakeMarkdown->transform($article); ?>
	</div>
</div>
