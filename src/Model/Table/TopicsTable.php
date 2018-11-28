<?php
namespace App\Model\Table;

use App\Model\Entity\Topic;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Collection\Collection;
use Cake\Utility\Text;

define('PURGE_FIRST', TRUE);
define('DONT_PURGE', FALSE);

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
		$list = FALSE;
//		$list = Cache::read('list', '_topic_');
		return $list ? $list : $this->find('list')->order('name');
	}
	
	/**
	 * Link all appropriate articles to the new or edited topic
	 * 
	 * If the topic was edited, we'll first delete all references to it. 
	 * This deletion action actually depends on the calling code, not 
	 * any logic here.
	 * 
	 * The calling controller must also pass the Flash component so 
	 * the we can report on the results of the action.
	 * 
	 * @param string $id
	 * @param boolean $purge PURGE_FIRST or DONT_PURGE
	 * @param object $flash the Flash component form the controller
	 * @return boolean False if the process failed
	 */
	public function updateReferences($id, $purge = DONT_PURGE, $flash) {
		// maybe kill old links to this topic
		$this->purgeReferences($id, $purge);
		
		// get all articles and the new topic
		$articles = $this->Articles->find('all')
				->select(['Articles.id', 'Articles.text', 'Articles.title']);
		$topic = $this->findById($id)
				->select(['Topics.id', 'Topics.name', 'Topics.slug']);
		
		$regexPattern = $this->regexPattern($topic->toArray());
		
		// reduce to the articles that match the topic
		$articles = new Collection($articles);
		$matches = $articles->reduce(function($accum, $entity) use ($regexPattern) {
			preg_match_all("/$regexPattern/i", $entity->text, $match);
			if (!empty($match[0])) {
				array_push($accum['id'], $entity->id);
				array_push($accum['title'], $entity->title);
			}
			return $accum;
		}, ['id' => [], 'title' => []]);
		
		// create all the new joins
		if (!empty($matches['id'])) {
			$this->hasMany('ArticlesTopics');
			$JoinTable = $this->ArticlesTopics;
			$articleIds = new Collection($matches['id']);
			$joinEntities = $articleIds->reduce(function($accum, $articleId) use ($id, $JoinTable){
				$join = ['article_id' => $articleId, 'topic_id' => $id];
				array_push($accum, $JoinTable->newEntity($join));
				return $accum;
			}, []);
		} else {
			$msg = 'No articles matched the topic at this time.';
			$flash->success($msg);
			return TRUE;
		}

		// count how many save successfully
		$count = 0;
		foreach($joinEntities as $joinEntity) {
			$count = $this->ArticlesTopics->save($joinEntity) ? $count + 1 : $count;
		}
		
		$topic = $topic->toArray()[0];
		if (count($matches['title'] === $count)) {
			$msg = "The $count titles — " . Text::toList($matches['title']) . ' — were placed in '
					. 'a topic ' . $topic->name . '.';
			$flash->success($msg);
			return TRUE;
		} else {
			$msg = "$count of the expected " . count($matches['title']) . ' titles were added to the '
					. 'topic ' . $topic->name . '. You should review ' . Text::toList($matches['title']) 
					. ' to see wich failed to be included.';
			$flash->error($msg);
			return FALSE;
		}
	}
	
	/**
	 * Create the regex matcher pattern for the topics
	 * 
	 * Topics are matched in the articles using regex. Case insensitive, 
	 * starts at word boundery, ends anywhere. This takes one or more 
	 * Topic entities and converts their name value into the regex.
	 * 
	 * `'(\bfirst name)|(\bsecond name)|(\bthird name)'`
	 * 
	 * @param array $entities
	 * @return string
	 */
	public function regexPattern($entities = []) {
		if (!is_array($entities)) {
			$entities = [$entities];
		}
		$entities = new Collection($entities);
		$patterns = $entities->reduce(function($accum, $value) {
			array_push($accum, "(\b$value->name)");
			return $accum;
		}, []);
		return implode('|', $patterns);
	}
	
	/**
	 * Drop all article links to the given topic
	 * 
	 * Used when the topic is edited or deleted
	 * 
	 * @param string $id
	 * @return boolean False if the process failed
	 */
	public function purgeReferences($id, $purge = TRUE) {
		if (!$purge) {
			return 0;
		}
		$this->hasMany('ArticlesTopics');
		return $this->ArticlesTopics->deleteAll(['topic_id' => $id]);		
	}
}
