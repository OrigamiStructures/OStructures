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
				$output = $this->modifyImageDom($output, $source);
				Cache::write(
						$source->markdownCacheKey(),
						"\n<!-- CACHED ARTICLE MARKDOWN -->\n$output\n<!-- END ARTICLE MARKDOWN -->\n",
						$source->markdownCacheConfig());				
			}
		} else {
			// a string was sent for processing. we're not caching those right now
			$output = $this->CakeMarkdown->transform($source);
		}
		return $output;
	}
	
	/**
	 * Add microdata markup to images in transformed markdown
	 * 
	 * Markdown buids simple <img> tags. We want them marked up with microdata.
	 *  
	 * If the image is in a paragraph with other text it will be returned as a 
	 * simple <img class="inline-img"> still inline to the paragraph.
	 * 
	 * If it is in an otherwise empty <p> it will be expanded to a figure 
	 * with a caption and copyright. The <p> will be dumped.
	 *	<figure>
	 *		<img />
	 *		<figcaption> </figcaption>
	 *	</figure>
	 * 
	 * Markdown doesn't support captions, but they can be added to the title 
	 * attribute delimited with ;; (for example title;;caption)
	 * 
	 * @param string $output
	 * @return string
	 */
	private function modifyImageDom($output, $source) {
		$copyright = '';
		if ($source instanceof \Cake\ORM\Entity) {
			$copyright = $this->_View->element('Common/copyright', ['entity' => $source, 'tag' => 'small']);
		}
		$output = preg_replace_callback(
			'/(<p>.*)(<img .*title\=")(.*)(" \/>)(.*<\/p>)/',
			function($matches) use ($copyright) {
				// name all the matched parts
				list($full, $start_p, $start_img, $title, $end_img, $end_p) = $matches;
				
				// further processing of some parts
				$class = strlen($start_p . $end_p) > 7 ? ' class="inline-img"' : '';
				$start_img = str_replace('<img', "<img itemprop=\"image\"$class", $start_img);
				list($title, $caption) = explode(';;', "$title;;");
				
				// construct a <p> with inline image or a full <figure>
				if ($class !== '') {
					return $start_p . "\n\t" . $start_img . $title . $end_img . "\n" . $end_p;
				} else {
					return sprintf(
						"<figure%s itemprop=\"image\" itemscope itemtype=\"https://schema.org/ImageObject\">\n"
						. "\t%s%s%s\n" // the <img> tag
						. "\t<figcaption itemprop=\"caption\"><p>%s</p>%s</figcaption>\n"
						. "</figure>",
						$class, $start_img, $title, $end_img, $caption, $copyright);
				}
			}, 
			$output);
			
		return $output;
	}
	
}
