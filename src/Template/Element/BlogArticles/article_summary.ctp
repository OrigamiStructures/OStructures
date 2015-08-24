<?php
    $output = Cake\Cache\Cache::read($article->id, 'article_summary');
    if($output){
        echo $output;
    } else {
        $this->start('summary_output');
?>
        <article itemprop="itemListElement" itemscope="" itemtype="http://schema.org/TechArticle">
            <h1 class="article-title" itemprop="headline">
                <a itemprop="url" href="<?=$this->request->webroot;?>article/<?=$article->slug; ?>">
                    <?= $article->title; ?>
                </a>
            </h1>
            <?= $this->Html->link('Edit', ['action' => 'edit', $article->id]); ?>
            <p>Last Updated: <span  itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span></p>
            <section itemprop="description">
                <?= $this->Markdown->transform($article->summary);?>
            </section>
        </article>
<?php
        $this->end();
        Cake\Cache\Cache::write($article->id, $this->fetch('summary_output'), 'article_summary');
        echo $this->fetch('summary_output');
        $this->assign('summary_output', '');
    }
?>