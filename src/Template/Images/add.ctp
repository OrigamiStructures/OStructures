<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Images'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?></li>
    </ul>
</div>
<div class="images form large-10 medium-9 columns">
    <?= $this->Form->create($image, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Image') ?></legend>
        <?php
            echo $this->Form->input('image', ['type' => 'file']);
            echo $this->Form->input('article_id', ['options' => $articles, 'empty' => true]);
            echo $this->Form->input('title');
            echo $this->Form->input('alt');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
