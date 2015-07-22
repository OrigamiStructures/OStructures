<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Menu'), ['action' => 'edit', $menu->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Menu'), ['action' => 'delete', $menu->id], ['confirm' => __('Are you sure you want to delete # {0}?', $menu->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Menus'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Menu'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Menus'), ['controller' => 'Menus', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Menu'), ['controller' => 'Menus', 'action' => 'add']) ?> </li>
    </ul>
</div>
<div class="menus view large-10 medium-9 columns">
    <h2><?= h($menu->name) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Parent Menu') ?></h6>
            <p><?= $menu->has('parent_menu') ? $this->Html->link($menu->parent_menu->name, ['controller' => 'Menus', 'action' => 'view', $menu->parent_menu->id]) : '' ?></p>
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= h($menu->name) ?></p>
            <h6 class="subheader"><?= __('Controller') ?></h6>
            <p><?= h($menu->controller) ?></p>
            <h6 class="subheader"><?= __('Action') ?></h6>
            <p><?= h($menu->action) ?></p>
            <h6 class="subheader"><?= __('Type') ?></h6>
            <p><?= h($menu->type) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($menu->id) ?></p>
            <h6 class="subheader"><?= __('Lft') ?></h6>
            <p><?= $this->Number->format($menu->lft) ?></p>
            <h6 class="subheader"><?= __('Rght') ?></h6>
            <p><?= $this->Number->format($menu->rght) ?></p>
        </div>
    </div>
</div>
<div class="related row">
    <div class="column large-12">
    <h4 class="subheader"><?= __('Related Menus') ?></h4>
    <?php if (!empty($menu->child_menus)): ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Parent Id') ?></th>
            <th><?= __('Lft') ?></th>
            <th><?= __('Rght') ?></th>
            <th><?= __('Name') ?></th>
            <th><?= __('Controller') ?></th>
            <th><?= __('Action') ?></th>
            <th><?= __('Type') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($menu->child_menus as $childMenus): ?>
        <tr>
            <td><?= h($childMenus->id) ?></td>
            <td><?= h($childMenus->parent_id) ?></td>
            <td><?= h($childMenus->lft) ?></td>
            <td><?= h($childMenus->rght) ?></td>
            <td><?= h($childMenus->name) ?></td>
            <td><?= h($childMenus->controller) ?></td>
            <td><?= h($childMenus->action) ?></td>
            <td><?= h($childMenus->type) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Menus', 'action' => 'view', $childMenus->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Menus', 'action' => 'edit', $childMenus->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Menus', 'action' => 'delete', $childMenus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childMenus->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
