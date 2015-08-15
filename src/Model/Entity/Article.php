<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake3xMarkdown\Model\Entity\Interfaces\MarkdownInterface;
use Cake3xMarkdown\Model\Entity\Interfaces\GeshiInterface;
use Cake\Utility\Inflector;
use Cake\Collection\Collection;
use Cake\Utility\Hash;
use Sluggable\Utility\Slug;

/**
 * Article Entity.
 */
class Article extends Entity implements MarkdownInterface, GeshiInterface {
//class Article extends Entity implements MarkdownInterface {
//class Article extends Entity {
	
	/**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'text' => true,
        'type' => true,
        'slug' => true,
        'summary' => true,
        'images' => true,
    ];

	public function markdownCacheConfig($options = NULL) {
		return '_markdown_';
	}

	public function markdownCacheKey($options = NULL) {
		return '_article' . $this->id . $this->modified->toUnixString( ) ;
	}

	public function markdownCaching($options = NULL) {
		return TRUE;
	}

	public function markdownSource($options = NULL) {
		return $this->display_text;
	}

	public function geshiTemplate($args) {
		$non_numbered = ['bash', 'regex'];
		list($start_delimeter, $language, $source_code, $end_delimeter) = $args;
		if (!in_array($language, $non_numbered)) {
			return 'template';
		} else {
			return 'bash';
		}
	}

	public function geshiLanguage($args) {
		list($start_delimeter, $language, $source_code, $end_delimeter) = $args;
		return $language;
	}

	public function toc() {
		$this->toc = empty($this->toc) ? serialize([]) : $this->toc;
		return new Collection(unserialize($this->toc));
	}
	
}
