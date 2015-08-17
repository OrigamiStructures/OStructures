<?php
$this->append('css');
echo $this->Html->css('main-page');
$this->end();
?>

<div class="inner-wrap">
    <title>Origami Structures: PHP Development and developer assistance</title>
    <meta content="Evolving thoughts on PHP development, focused on CakePHP v3." name="description">
    <div class="fixed">
        <nav class="top-bar" data-topbar="">
            <ul class="title-area">
                <li class="name">
                    <h1>
                        <a href="#">
                            <?=$this->Html->image("crane_black_whiteOutline_transparent_40_40.png");?>
                            <span class="logo-type">Origami Structures</span>
                        </a>
                    </h1>
                </li>
            </ul>
            <section class="topbar-section">
                <ul class="right">
                    <li class="has-form">
                        <a class="small button" href="#">I need help!</a>
                    </li>
                </ul>
            </section>
        </nav>
    </div>
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
                    <?= $this->element('Common/sidebar', ['data' => []]); ?>
                </div>
            </div>
            <div class="large-9 medium-8 columns">
                <?php
                    foreach ($articles as $key => $article) {
                        echo $this->element('BlogArticles/article_summary', ['article' => $article]);
                        echo $this->element('Authors/author_view', ['authors' => $article->authors]);
                    }
                ?>
            </div>
        </div>

    </section>
</div>