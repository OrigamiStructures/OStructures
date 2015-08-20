<!-- Element/BlogArticles/article_toc -->
<?php 
use Sluggable\Utility\Slug;
use Cake\Cache\Cache;

if (!isset($toc)) {
	return '';
}
$output = Cache::read($article->id, 'toc_output');
if ($output) :
	echo "<!-- CACHED -->\n$output";
else:
	$this->start('toc');
?>
<!-- START OF ARTICLE TOC -->
<section class="toc" id="<?= Slug::generate('toc-:title', $article); ?>">
    <h1>Article Table of Contents</h1>
    <ul>
        <?php
        $toc_array = is_object($toc) ? $toc->toArray() : [];

        foreach ($toc as $index => $anchor) :
            list($h_level, $title, $slug) = $anchor;

            // The first LI
            if ($index === 0) :
                echo "\t<li><h2><a href=\"#$slug\">$title</a></h2>\n\t";
                continue;
            endif;

            // calc how many levels we changed
            // compares the number of # chars
            $dif = strlen($h_level) - strlen($toc_array[$index - 1][0]);

            // Step back up to a parent level
            if ($dif < 0) :
                while ($dif < 0) :
                    echo "</li>\n</ul>\n\t";
                    $dif++;
                endwhile;
                echo "</li>\n\t<li><a href=\"#$slug\">$title</a>\n\t";

            // Step into some child level
            elseif ($dif > 0) :
                while ($dif > 1) :
                    echo "<ul>\n\t<li>\n\t";
                    $dif--;
                endwhile;
                echo "<ul>\n\t<li><a href=\"#$slug\">$title</a>";

            // Sibling LI
            elseif ($dif === 0) :
                echo "</li>\n\t<li><a href=\"#$slug\">$title</a>\n\t";
            endif;
        endforeach;

        // Close to root from wherever we ended
        if (!empty($toc_array)) :
            $dif = strlen($toc_array[count($toc_array) - 1][0]);
            while ($dif > 0) :
                echo "</li>\n</ul>\n\t";
                $dif--;
            endwhile;
        endif;
		$slug = Slug::generate('info-:title', $article);
		echo "<ul class=\"info-link\"><li><a href=\"#$slug\">Publication details</a></li></ul>";
        ?>
</section>
<!-- END OF ARTICLE TOC -->
<?php
	$this->end();
	Cache::write($article->id, $this->fetch('toc'), 'toc_output');
	echo $this->fetch('toc');
endif;
