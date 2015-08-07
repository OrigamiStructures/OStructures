<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $image->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Images'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?></li>
    </ul>
</div>
<div class="images form large-7 medium-6 columns">
    <?= $this->Form->create($image, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit Image') ?></legend>
        <?php
            echo $this->Form->input('image', ['type' => 'file']);
            echo $this->Form->input('image_dir');
            echo $this->Form->input('article_id', ['options' => $articles, 'empty' => true]);
            echo $this->Form->input('mimetype');
            echo $this->Form->input('filesize');
            echo $this->Form->input('width');
            echo $this->Form->input('height');
            echo $this->Form->input('title');
            echo $this->Form->input('date');
            echo $this->Form->input('alt');
            echo $this->Form->input('upload');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<div class="images large-3 columns">
	<?php $path = "images/image/{$image['image_dir']}/{$image['image']}"; ?>
	<?= $this->Html->image($path); ?>
</div>
