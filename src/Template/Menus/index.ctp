<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Menu'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="menus index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('parent_id') ?></th>
            <th><?= $this->Paginator->sort('lft') ?></th>
            <th><?= $this->Paginator->sort('rght') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('controller') ?></th>
            <th><?= $this->Paginator->sort('action') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($menus as $menu): ?>
        <tr>
            <td><?= $this->Number->format($menu->id) ?></td>
            <td>
                <?= $menu->has('parent_menu') ? $this->Html->link($menu->parent_menu->name, ['controller' => 'Menus', 'action' => 'view', $menu->parent_menu->id]) : '' ?>
            </td>
            <td><?= $this->Number->format($menu->lft) ?></td>
            <td><?= $this->Number->format($menu->rght) ?></td>
            <td><?= h($menu->name) ?></td>
            <td><?= h($menu->controller) ?></td>
            <td><?= h($menu->action) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $menu->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $menu->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $menu->id], ['confirm' => __('Are you sure you want to delete # {0}?', $menu->id)]) ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
