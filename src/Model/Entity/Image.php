<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Image Entity.
 */
class Image extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'image' => true,
        'image_dir' => true,
        'article_id' => true,
        'mimetype' => true,
        'filesize' => true,
        'width' => true,
        'height' => true,
        'title' => true,
        'date' => true,
        'alt' => true,
        'upload' => true,
        'article' => true,
    ];
}
