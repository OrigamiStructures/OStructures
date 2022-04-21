<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Author'), ['action' => 'edit', $author->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Author'), ['action' => 'delete', $author->id], ['confirm' => __('Are you sure you want to delete # {0}?', $author->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Authors'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Author'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?> </li>
    </ul>
</div>
<div class="authors view large-10 medium-9 columns">
    <h2><?= h($author->name) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= h($author->name) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($author->id) ?></p>
            <h6 class="subheader"><?= __('Active') ?></h6>
            <p><?= $this->Number->format($author->active) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($author->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($author->modified) ?></p>
            <h6 class="subheader"><?= __('Updated') ?></h6>
            <p><?= h($author->updated) ?></p>
        </div>
    </div>
    <div class="row texts">
        <div class="columns large-9">
            <h6 class="subheader"><?= __('Signature') ?></h6>
            <?= $this->Text->autoParagraph(h($author->signature)) ?>
        </div>
    </div>
</div>
<div class="related row">
    <div class="column large-12">
    <h4 class="subheader"><?= __('Related Articles') ?></h4>
    <?php if (!empty($author->articles)): ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Title') ?></th>
            <th><?= __('Text') ?></th>
            <th><?= __('Type') ?></th>
            <th><?= __('Slug') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Modified') ?></th>
            <th><?= __('Updated') ?></th>
            <th><?= __('Summary') ?></th>
            <th><?= __('Display Text') ?></th>
            <th><?= __('Publish') ?></th>
            <th><?= __('Toc') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($author->articles as $articles): ?>
        <tr>
            <td><?= h($articles->id) ?></td>
            <td><?= h($articles->title) ?></td>
            <td><?= h($articles->text) ?></td>
            <td><?= h($articles->type) ?></td>
            <td><?= h($articles->slug) ?></td>
            <td><?= h($articles->created) ?></td>
            <td><?= h($articles->modified) ?></td>
            <td><?= h($articles->updated) ?></td>
            <td><?= h($articles->summary) ?></td>
            <td><?= h($articles->display_text) ?></td>
            <td><?= h($articles->publish) ?></td>
            <td><?= h($articles->toc) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Articles', 'action' => 'view', $articles->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Articles', 'action' => 'edit', $articles->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Articles', 'action' => 'delete', $articles->id], ['confirm' => __('Are you sure you want to delete # {0}?', $articles->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
