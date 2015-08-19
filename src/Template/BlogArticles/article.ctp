<section class="scroll-container" role="main">
    <div class="row">
        <div class="large-3 medium-4 columns">
            <div class="sidebar">
                <?= $this->element('Common/sidebar', ['recent' => $recent]); ?>
            </div>
        </div>
        <div class="large-9 medium-8 columns">
            <?= $this->element('BlogArticles/article_view', ['article' => $article]); ?>
        </div>
    </div>
</section>
