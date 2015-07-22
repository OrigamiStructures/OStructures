<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $menu->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $menu->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Menus'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Parent Menus'), ['controller' => 'Menus', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Menu'), ['controller' => 'Menus', 'action' => 'add']) ?></li>
    </ul>
</div>
<div class="menus form large-10 medium-9 columns">
    <?= $this->Form->create($menu) ?>
    <fieldset>
        <legend><?= __('Edit Menu') ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentMenus, 'empty' => true]);
            echo $this->Form->input('lft');
            echo $this->Form->input('rght');
            echo $this->Form->input('name');
            echo $this->Form->input('controller');
            echo $this->Form->input('action');
            echo $this->Form->input('type');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
