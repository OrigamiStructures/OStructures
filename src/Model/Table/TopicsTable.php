<?php
namespace App\Model\Table;

use App\Model\Entity\Topic;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Cache\Cache;
use Cake\Event\Event;

/**
 * Topics Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Articles
 */
class TopicsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('topics');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Articles', [
            'foreignKey' => 'topic_id',
            'targetForeignKey' => 'article_id',
            'joinTable' => 'articles_topics'
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
            ->allowEmpty('name');
            
        $validator
            ->allowEmpty('slug');

        return $validator;
    }
	
	public function beforeSave(Event $event, $entity) {
        if ($entity->isNew() || $entity->dirty('name')) {
			Cache::delete('list', '_topic_');
		}
	}
	
	public function findTopicList() {
		$list = Cache::read('list', '_topic_');
		return $list ? $list : $this->find('list')->order('name');
	}
}
