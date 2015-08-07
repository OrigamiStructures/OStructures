<?php
$this->append('css');
echo $this->Html->css('article-authoring');
$this->end();
?>
<div class="row">
	<div class="small-12 medium-4 large-2 columns resource-zone">
		<div class="row">
			<div class="small-6 column image_resource">
				<?= $this->element('Images/image_resources', ['images' => $article->images]); ?>
			</div>
			<div class="small-6 column article_resource">
				<p>Future text resources</p>
			</div>
		</div>
		
	</div>
	<div class="medium-8 large-5 columns edit-zone">
		<?= $this->Form->create($article); ?>
		<?= $this->element('BlogArticles/edit_fieldset') ?>
		<?= $this->Form->button(__('Submit'), ['class' => 'button expand']); ?>
		<?= $this->Form->end(); ?>
	</div>
	<div class="large-5 columns preview-zone"">
		<?= $this->CakeMarkdown->transform($article); ?>
	</div>
</div>
