<?php
$this->append('css');
echo $this->Html->css('article-authoring');
$this->end();
?>
<div class="row sm-tall-100">
	<div class="resource-zone">
		<div id="resource-placeholder"></div>
		<div class="row">
			<div class="small-6 column image_resource">
				<?= $this->element('Resources/image_resources', ['images' => $article->images]); ?>
			</div>
			<div class="small-6 column article_resource">
				<p>Future text resources</p>
			</div>
		</div>
		
	</div>
	<div class="edit-zone">
		<?= $this->Form->create($article); ?>
		<?= $this->element('BlogArticles/edit_fieldset') ?>
		<?= $this->Form->input('continue', ['label' => 'Preview and continue editing', 'type' => 'checkbox']); ?>
		<?= $this->Form->button(__('Submit'), ['class' => 'button expand']); ?>
		<?= $this->Form->end(); ?>
	</div>
	<div class="preview-zone">
		<?= $this->CakeMarkdown->transform($article); ?>
	</div>
</div>
