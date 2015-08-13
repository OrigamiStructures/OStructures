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
			
			$entity->text = preg_replace(
					'/<span class="anchor".*<\/span>\W/', 
					'', 
					$entity->text
			);
			
			$entity->text = preg_replace(
					'/(#+.+)/', 
//					'<span class="anchor" id="=====' . "\"></span>\n$1", 
					'<span class="anchor" id="====="><a href="#' . Slug::generate('toc-:title', $entity) . "\">Table of contents</a></span>\n$1", 
					$entity->text
			);
			preg_match_all('/\n(#+.+)/', $entity->text, $headings);
			$text = explode('=====', $entity->text);
			$max = count($text) - 1;
			$count = 0;
			$string = '';
			while ($count < $max) {
				$string .= $text[$count] . Slug::generate($headings[0][$count++]);
			}
			$entity->text = $string . $text[$count];
//			debug($entity->text);


//			debug($string);
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
