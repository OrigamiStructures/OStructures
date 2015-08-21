<?php
namespace App\View\Helper;
use Cake3xMarkdown;
use Cake\Cache\Cache;
use Cake3xMarkdown\Model\Entity\Interfaces\MarkdownInterface;
use Cake3xMarkdown\Model\Entity\Interfaces\GeshiInterface;

use Cake\View\Helper;

/**
 * CakePHP Markdown
 * @author dondrake
 */
class MarkdownHelper extends Helper {
	
	public $helpers = ['Cake3xMarkdown.CakeMarkdown'];
	
	public function transform($source){
//		debug(get_declared_interfaces());die;
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
		return "<!-- modified -->\n$output";
	}
}
