<?php
//debug($images);
foreach ($articles as $article) {
    $webrootConstant = "http://localhost/OStructures" . DS . "blogArticle" . DS . "view_article" . DS;
    $markdownArticleLink = "[{$article->title}]({$webrootConstant}{$article->slug} \"{$article->title}\")";
    
    echo '<div class="row">';
    echo $this->Html->tag('div', $this->CakeMarkdown->transform($article->summary), ['class' => 'resourceArticle']);
        echo $this->Html->para('MarkdownArticleLink', $markdownArticleLink);
    echo '</div>';
}