<?php
echo $this->Html->tag('section', NULL, ['class' => 'author_block', 'itemprop' => 'author', 'itemscope', 'itemtype' => "https://schema.org/Person"]);
foreach ($authors as $key => $author) {
    $icon_name = strtolower(preg_replace('/ /', '', $author->name));
    $icon = "author_icon_$icon_name.jpg";
    
    echo '<div class="author row">';
        echo $this->Html->image($icon, array('class' => 'small-1 columns'));
        echo $this->Html->div('small-11 columns', NULL);
            echo $this->Html->div('row', NULL);
                echo $this->Html->para('author_name', $author->name);
                echo $this->Markdown->transform($author->signature);
            echo '</div>';    
        echo '</div>';
    echo '</div>';
}
echo '</section>';