<?php use Sluggable\Utility\Slug; ?>
<!-- START OF ARTICLE TOC -->
<ul id="<?= Slug::generate('toc-:title', $article); ?>">
	<?php
	$toc_array = $toc->toArray();

	foreach ($toc as $index => $anchor) :
		list($h_level, $title, $slug) = $anchor;

		// The first LI
		if ($index === 0) :
			echo "\t<li><a href=\"#$slug\">$title</a>\n\t";
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
	$dif = strlen($toc_array[count($toc_array) - 1][0]);
	while ($dif > 0) :
		echo "</li>\n</ul>\n\t";
		$dif--;
	endwhile;
	?>
<!-- END OF ARTICLE TOC -->
