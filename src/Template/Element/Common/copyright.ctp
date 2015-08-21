<?php
/**
 * Call this with an $entity that has a 'modified' property and the $tag name 
 * (eg: 'p', 'small', 'span') that you want to wrap the whole line.
 */
?>
<<?= $tag; ?> class="copyright">Copyright 
<span itemprop="copyrightYear"><?= $this->Time->format($entity->modified, 'yyyy'); ?></span>, 
<span itemprop="copyrightHolder" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">Origami Structures</span> 
	(<a itemprop="url" href="http://origamistructures.com">origamistructures.com</a>)</span>
</<?= $tag; ?>>

