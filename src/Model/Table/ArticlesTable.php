<?php
namespace App\Model\Table;

use App\Model\Entity\Article;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Sluggable;
use Cake\Event\Event;
//use Cake\Utility\Inflector;
use Sluggable\Utility\Slug;
use Cake\Collection\Collection;

/**
 * Articles Model
 *
 * @property \Cake\ORM\Association\HasMany $Images
 * @property \Cake\ORM\Association\BelongsToMany $Topics
 */
class ArticlesTable extends Table
{

    public function beforeSave(Event $event, Article $entity) {

        if ($entity->isNew() || $entity->dirty('text')) {
						
			$stub_toc_anchors = preg_replace(
					'/((^#)|(\n#))(#*.+)/', 
//					'<span class="anchor" id="=====' . "\"></span>\n$1", 
					'<span class="anchor" id="====="><a href="#' . Slug::generate('toc-:title', $entity) . "\">Table of contents</a></span>\n#$4", 
					$entity->text
			);
			
			preg_match_all('/((^#)|(\n#))(#*.+)/', $entity->text, $headings);
//			debug($headings);
			$text = explode('=====', $stub_toc_anchors);
//			debug($text);
			$max = count($text) - 1;
			$count = 0;
			$entity->display_text = '';
			$toc = ['#' . $entity->title];
			while ($count < $max) {
				$entity->display_text .= $text[$count] . Slug::generate($headings[4][$count]);
				array_push($toc, '#' . trim($headings[4][$count++], "\r\n"));
			}
			$entity->display_text .= $text[$count];
			$toc = new Collection($toc);
			$toc = $toc->map(function ($value, $key) {
				return [preg_replace('/^(#+).*/', '$1', $value), preg_replace('/^#+/', '', $value), Slug::generate($value)];
			});

			$entity->toc = serialize($toc->toArray());

			debug('insure the image links');
			debug('setup the topics');
		}
	}

	/**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('articles');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        // $this->hasMany('Images', [
        //     'foreignKey' => 'article_id'
        // ]);
		$this->addBehavior('Sluggable.Sluggable', [
            'pattern' => ':title',
			'overwrite' => true
        ]);
        $this->belongsToMany('Topics', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'topic_id',
            'joinTable' => 'articles_topics'
        ]);
        $this->belongsToMany('Images', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'image_id',
            'joinTable' => 'articles_images'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->allowEmpty('title');
            
        $validator
            ->allowEmpty('text');
            
        $validator
            ->allowEmpty('type');
            
        $validator
            ->allowEmpty('slug');
            
        $validator
            ->allowEmpty('summary');

        return $validator;
    }
}
