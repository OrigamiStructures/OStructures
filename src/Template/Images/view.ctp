<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Image'), ['action' => 'edit', $image->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Image'), ['action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?> </li>
    </ul>
</div>
<div class="images view large-10 medium-9 columns">
    <h2><?= h($image->title) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Image') ?></h6>
            <p><?= h($image->image) ?></p>
            <h6 class="subheader"><?= __('Image Dir') ?></h6>
            <p><?= h($image->image_dir) ?></p>
            <h6 class="subheader"><?= __('Article') ?></h6>
            <p><?= $image->has('article') ? $this->Html->link($image->article->title, ['controller' => 'Articles', 'action' => 'view', $image->article->id]) : '' ?></p>
            <h6 class="subheader"><?= __('Mimetype') ?></h6>
            <p><?= h($image->mimetype) ?></p>
            <h6 class="subheader"><?= __('Title') ?></h6>
            <p><?= h($image->title) ?></p>
            <h6 class="subheader"><?= __('Alt') ?></h6>
            <p><?= h($image->alt) ?></p>
        </div>
        <div class="large-2 columns numbers end">
			<div class="row">
				<div class="columns small-6">
					<h6 class="subheader"><?= __('Id') ?></h6>
					<p><?= $this->Number->format($image->id) ?></p>
					<h6 class="subheader"><?= __('Filesize') ?></h6>
					<p><?= $this->Number->format($image->filesize) ?></p>
					<h6 class="subheader"><?= __('Width') ?></h6>
					<p><?= $this->Number->format($image->width) ?></p>
					<h6 class="subheader"><?= __('Height') ?></h6>
					<p><?= $this->Number->format($image->height) ?></p>
					<h6 class="subheader"><?= __('Date') ?></h6>
					<p><?= $this->Number->format($image->date) ?></p>
					<h6 class="subheader"><?= __('Upload') ?></h6>
					<p><?= $this->Number->format($image->upload) ?></p>
				</div>
<!--			</div>
			<div class="row dates">-->
				<div class="columns small-6">
					<h6 class="subheader"><?= __('Modified') ?></h6>
					<p><?= h($image->modified) ?></p>
					<h6 class="subheader"><?= __('Created') ?></h6>
					<p><?= h($image->created) ?></p>
				</div>
			</div>
        </div>
		<div class="large-3 columns images end">
			<?php $path = "images/image/{$image['image_dir']}/{$image['image']}"; ?>
			<?= $this->Html->image($path); ?>
		</div>
    </div>
</div>
