<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake3xMarkdown\Model\Entity\Interfaces\MarkdownInterface;
use Cake3xMarkdown\Model\Entity\Interfaces\GeshiInterface;

/**
 * Article Entity.
 */
class Article extends Entity implements MarkdownInterface, GeshiInterface {
	
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
		return '_article' . $this->id . $this->modified->nice();
	}

	public function markdownCaching($options = NULL) {
		return TRUE;
	}

	public function markdownSource($options = NULL) {
		return $this->text;
	}

}
