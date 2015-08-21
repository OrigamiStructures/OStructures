<?php
namespace App\View\Helper;
use Cake3xMarkdown;
use Cake\Cache\Cache;
use Cake3xMarkdown\Model\Entity\Interfaces\MarkdownInterface;
use Cake3xMarkdown\Model\Entity\Interfaces\GeshiInterface;

use Cake\View\Helper;

/**
 * CakeMarkdown wrapper to do caching
 * 59bf462d Wrap CakeMarkdown helper to implement new caching scheme
 * https://github.com/OrigamiStructures/Cake3xMarkdown/issues/7 is an
 * alternative plan that would make this helper unnecessary by allowing
 * us to register an afterTransform() event.
 * 
 * CakePHP Markdown
 * @author dondrake
 */
class MarkdownHelper extends Helper {
	
	public $helpers = ['Cake3xMarkdown.CakeMarkdown'];
	
	/**
	 * Override the plugins caching to gain time for post transform processing
	 * 
	 * This ignores the mardownCaching setting in the Entity and assumes if
	 * we see an object, we will cache. Not a lot of finess...
	 * 
	 * If we use this to transform any article field other than a display_text 
	 * and we send the Entity object, things are going to fail. But the calls 
	 * do accept parameters so we could point to other columns, other cache 
	 * keys and other cache configs if need be. It will just require Ariticle 
	 * Entity modifications and cnages to this method. 
	 * 
	 * @param MarkdownInterface $source
	 * @return type
	 * @throws \BadFunctionCallException
	 */
	public function transform($source){
		if (is_object($source)) {
			if (!$source instanceof MarkdownInterface) {
				throw new \BadFunctionCallException(
						'Markdown::transform() argument must be an object that implements MardownInterface or a string');
			}
			$output = Cache::read($source->markdownCacheKey($this), $source->markdownCacheConfig($this));
			if (!$output) {
				$output = $this->CakeMarkdown->transform($source);
				$output = $this->modifyImageDom($output);
				Cache::write($source->markdownCacheKey(), $output, $source->markdownCacheConfig());				
			}
		} else {
			$output = $this->CakeMarkdown->transform($source);
		}
		return $output;
	}
	
	private function modifyImageDom($output) {
		$collect = [];
//		preg_match_all(
//				'/(<p.*)(<img .*\/>)(.*\/p>)/',
////				'/(<p>.*)(<img .*title\="(.*)" \/>)(.*<\/p>)/', 
//				$output, $matches);
//		debug($matches);
		$output = preg_replace_callback(
				'/(<p>.*)(<img .*title\=")(.*)(" \/>)(.*<\/p>)/',
				function($matches){
					list($full, $start_p, $start_img, $title, $end_img, $end_p) = $matches;
					list($title, $caption) = explode(';;', "$title;;");
					$start_img = str_replace('<img', '<img itemprop="image"', $start_img);
					return sprintf(
						"<figure itemprop=\"image\" itemscope itemtype=\"https://schema.org/ImageObject\">\n"
						. "\t%s%s%s\n" // the <img> tag
						. "\t<figcaption itemprop=\"caption\">%s</figcaption>\n"
						. "</figure>",
						$start_img, $title, $end_img, $caption);
				}, 
				$output);
//				debug($collect);
//		debug($result);
		return "<!-- modified -->\n$output";
	}
	
	/**
	 * <p>This is a truely inline <img title="" alt="" src="/OStructures/img/images/image/11bc8bf5-2e33-42db-8963-a20473d99b0b/DSC03227.JPG"> image</p>
	 */
	
	/**
	 * <p><img title="" alt="" src="/OStructures/img/images/image/95234fb9-c4cc-4135-a4ca-82371c5d5520/DSC02260-4.jpg"></p>
	 */
}
