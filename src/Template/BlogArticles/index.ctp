<?php
$this->append('css');
echo $this->Html->css('main-page');
$this->end();
?>

<section class="homepage-hero">
    <div class="row">
        <div class="medium-9 large-8 columns">
            <h1>Origami Structures</h1>
            <h3>Providing advanced intelligence, guidance and thoughts
                </br>
                on programming for the web
            </h3>
            <h3>Focused on CakePHP and web standards</h3>
        </div>
    </div>
</section>
<section class="scroll-container" role="main">
    <div class="row">
        <div class="large-3 medium-4 columns">
            <div class="sidebar">
                <?= $this->element('Common/sidebar'); ?>
            </div>
        </div>
        <div class="large-9 medium-8 columns">
            <?php
                foreach ($articles as $key => $article) {
                    echo $this->element('BlogArticles/article_summary', ['article' => $article]);
                }
            ?>
        </div>
    </div>
</section>
