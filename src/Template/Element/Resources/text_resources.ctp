<?php
//debug($images);
foreach ($articles as $article) :
    $webrootConstant = "http://localhost/OStructures" . DS . "blogArticle" . DS . "view_article" . DS;
    $markdownArticleLink = "[{$article->title}]({$webrootConstant}{$article->slug} \"{$article->title}\")";
?>
    
    <div class="row">
		<?= $this->Html->para('resourceArticle', $article->title); ?>
		<?= $this->Html->para('MarkdownArticleLink', $markdownArticleLink); ?>
    </div>

<?php endforeach; ?>