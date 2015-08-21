<?php
echo $this->Html->tag('div', NULL, ['class' => 'author_block', 'itemprop' => 'author', 'itemscope', 'itemtype' => "https://schema.org/Person"]);
foreach ($authors as $key => $author) {
    echo $this->Html->tag('author_initial', substr($author->name, 0, 1));
    echo $this->Html->tag('div', $author->name, ['class' => 'author_name']);
    echo $this->Markdown->transform($author->signature);
}
echo '</div>';