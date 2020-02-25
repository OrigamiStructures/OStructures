<?php
use Cake\Utility\Text;
/* @var \Cake\View\View $this */
/* @var array $filterTopics */
/* @var array $distinctArticles */
/* @var \App\Model\Entity\Article $article */
?>

<section class="scroll-container" role="main">
    <div class="row">
        <div class="large-3 medium-4 columns">
            <div class="sidebar">
                <?= $this->element('Common/sidebar'); ?>
            </div>
        </div>
        <div class="large-9 medium-8 columns">
            <article>
            <?php
            echo $this->Html->tag('h3', 'Target Topics: ' . Text::toList($filterTopics)) . PHP_EOL;
            $topicsReferenced = 0;
            foreach ($distinctArticles as $article) {
                if($article->topics != $topicsReferenced) {
                    if ($topicsReferenced != 0) {
                        echo '</ul>' . PHP_EOL;
                    }
                    $topicsReferenced = $article->topics;
                    $additional = $topicsReferenced - count($filterTopics);
                    echo $this->Html->para(null, "Referencing $additional additional topics") . PHP_EOL;
                    echo '<ul>' . PHP_EOL;
                }
	            echo '<li>' . PHP_EOL;
		            echo $this->Html->link($article->title, ['action' => 'view', $article->slug]) . PHP_EOL;
                echo '</li>' . PHP_EOL;
            }
            echo '</ul>' . PHP_EOL;
            ?>
            </article>
        </div>
    </div>
</section>


