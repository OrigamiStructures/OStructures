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
		<p>Inputs</p>
		<!-- <?= debug($article); ?> -->
	</div>
	<div class="large-5 columns preview-zone"">
		<div class="row">
			<p class="column medium-4" style="background: lavender;">Preview1</p>
			<p class="column medium-4" style="background: linen;">Preview2</p>
			<p class="column medium-4" style="background: lightsteelblue;">Preview3</p>
		</div>
	</div>
</div>
