<?php
use Cake\Cache\Cache;

    $output = Cache::read($article->id, 'article_summary');
    if($output){
        echo $output;
    } else {
        $this->start('summary_output');
?>
<article class="summary" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/TechArticle">
            <h1 itemprop="headline">
                <a itemprop="url" href="<?=$this->request->webroot;?>article/<?=$article->slug; ?>">
                    <?= $article->title; ?>
                </a>
            </h1>
				<p>
					<?= ($this->request->env('HTTP_HOST')) === 'localhost' ? 
						$this->Html->link('Edit', ['action' => 'edit', $article->id]) . ' | ' : ''; ?>
					Last Updated: 
					<span  itemprop="datePublished"><?= $this->Time->format($article->modified, 'yyyy-MM-dd') ?></span>
				</p>
            <section itemprop="description">
                <?= $this->Markdown->transform($article->summary);?>
				<h5 class="more"><a itemprop="url" href="<?=$this->request->webroot;?>article/<?=$article->slug; ?>">
					... more
                </a></h5>

            </section>
        </article>
<?php
        $this->end();
        Cache::write($article->id, $this->fetch('summary_output'), 'article_summary');
        echo $this->fetch('summary_output');
        $this->assign('summary_output', '');
    }
	