<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Article'), ['action' => 'edit', $article->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Article'), ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete # {0}?', $article->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Articles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Article'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Images'), ['controller' => 'Images', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Image'), ['controller' => 'Images', 'action' => 'add']) ?> </li>
    </ul>
</div>
<div class="articles view large-10 medium-9 columns">
    <h2><?= h($article->title) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Title') ?></h6>
            <p><?= h($article->title) ?></p>
            <h6 class="subheader"><?= __('Type') ?></h6>
            <p><?= h($article->type) ?></p>
            <h6 class="subheader"><?= __('Slug') ?></h6>
            <p><?= h($article->slug) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($article->id) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($article->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($article->modified) ?></p>
            <h6 class="subheader"><?= __('Updated') ?></h6>
            <p><?= h($article->updated) ?></p>
        </div>
    </div>
    <div class="row texts">
        <div class="columns large-9">
            <h6 class="subheader"><?= __('Text') ?></h6>
            <?= $this->CakeMarkdown->transform($article->text) ?>
        </div>
    </div>
    <div class="row texts">
        <div class="columns large-9">
            <h6 class="subheader"><?= __('Summary') ?></h6>
            <?= $this->Text->autoParagraph(h($article->summary)) ?>
        </div>
    </div>
</div>
<div class="related row">
    <div class="column large-12">
    <h4 class="subheader"><?= __('Related Images') ?></h4>
    <?php if (!empty($article->images)): ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Img File') ?></th>
            <th><?= __('Id') ?></th>
            <th><?= __('Article Id') ?></th>
            <th><?= __('Modified') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Mimetype') ?></th>
            <th><?= __('Filesize') ?></th>
            <th><?= __('Width') ?></th>
            <th><?= __('Height') ?></th>
            <th><?= __('Title') ?></th>
            <th><?= __('Date') ?></th>
            <th><?= __('Alt') ?></th>
            <th><?= __('Upload') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($article->images as $images): ?>
        <tr>
            <td><?= h($images->img_file) ?></td>
            <td><?= h($images->id) ?></td>
            <td><?= h($images->article_id) ?></td>
            <td><?= h($images->modified) ?></td>
            <td><?= h($images->created) ?></td>
            <td><?= h($images->mimetype) ?></td>
            <td><?= h($images->filesize) ?></td>
            <td><?= h($images->width) ?></td>
            <td><?= h($images->height) ?></td>
            <td><?= h($images->title) ?></td>
            <td><?= h($images->date) ?></td>
            <td><?= h($images->alt) ?></td>
            <td><?= h($images->upload) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Images', 'action' => 'view', $images->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Images', 'action' => 'edit', $images->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Images', 'action' => 'delete', $images->id], ['confirm' => __('Are you sure you want to delete # {0}?', $images->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
