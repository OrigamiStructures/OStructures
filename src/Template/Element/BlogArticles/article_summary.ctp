<?php
use Cake\Cache\Cache;

    $output = Cache::read($article->id, 'article_summary');
    if($output){
        echo $output;
    } else {
        $this->start('summary_output');
		$article_path = ['action' => 'view', $article->slug];
?>
<article class="summary" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/TechArticle">
            <h1 itemprop="headline">
				<?= $this->Html->link($article->title, $article_path, ['itemprop' => 'url' ]); ?>
            </h1>
				<p>
					<?= $this->Article->editLink($article); ?>
					Last Updated: 
					<span  itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span>
				</p>
            <section itemprop="description">
                <?= $this->Markdown->transform($article->summary);?>
				<h5 class="more">
					<?= $this->Html->link('...more', $article_path, ['itemprop' => 'url' ]); ?>
                </h5>

            </section>
        </article>
<?php
        $this->end();
        Cache::write($article->id, $this->fetch('summary_output'), 'article_summary');
        echo $this->fetch('summary_output');
        $this->assign('summary_output', '');
    }
	