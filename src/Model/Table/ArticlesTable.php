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
use Cake\Cache\Cache;
use Cake\Utility\Hash;

/**
 * Articles Model
 *
 * @property \Cake\ORM\Association\HasMany $Images
 * @property \Cake\ORM\Association\BelongsToMany $Topics
 */
class ArticlesTable extends Table
{

	/**
	 * Regex pattern to find h1-h6 in markdown
	 *
	 * The heading come back on index 4 of the match array
	 * and they will be missing one # from the front of the
	 * match string. The match will also have a newline at the end.
	 *
	 * @var string
	 */
	protected $heading_detection_pattern = '/((^#)|(\n#))(#*.+)/';

	/**
	 * Regex pattern to find markdown image links
	 *
	 * Depends on the image src having a uuid in it. The uuid
	 * is also captured for logic that does automatic record associations
	 *
	 * exaple markdown image link
	 * ![alt attribute text](/path/11bc8bf5-2e33-42db-8963-a20473d99b0b/img.jpg "title attribute")
	 *
	 * @var string
	 */
	protected $image_link_detection_pattern = '/!\[.*\/([a-f0-9\-]{36})\//';

	/**
	 * Regex pattern to find markdown article links
	 *
	 * This will capture the article title and use is in the association logic.
	 * The title is assumed to be unique. A bit of a risk, but the best we have.
	 * Also, this will capture foreign urls too so the logic must deal with that.
	 *
	 * example markdown article link
	 * [Aticle Title](http://path/to/article/page "title attribute")
	 *
	 * @var string
	 */
	protected $article_link_detection_pattern = '/[^!]\[(.*)\]\(.*\)/';

	/**
	 * Do the background processing to fluff the markdown article
	 *
	 * Adds
	 *	- automatic article TOC
	 *  - automatic article categorization (pending)
	 *	- automatic image record linking (pending)
	 *	- automatic referenced article record linking (pending)
	 *  - and possibly image node markup? (no. this is not a viable plan)
	 *
	 * @param Event $event
	 * @param Article $entity
	 */
	public function beforeSave(Event $event, Article $entity) {
		if ($entity->isDirty('created')) {
			return $entity;
		}

		if (!$entity->isDirty('text') && ($entity->isDirty('title') || $entity->dirty('summary'))) {
			$this->buildToc($entity);

			Cache::clearGroup('recent_articles', 'article_lists');
//			Cache::delete($entity->id, 'article_markdown'); // doesn't effect mardown section
			Cache::delete($entity->id, 'article_summary');
			Cache::delete($entity->id, 'toc_output');
		}
        if ($entity->isNew() || $entity->isDirty('text')) {
			$this->manageTocAnchors($entity);
			$entity = $this->manageImageAssociations($entity);
			$this->manageTopicAssociations($entity);
			Cache::clearGroup('recent_articles', 'article_lists');
			Cache::delete((string) $entity->id, 'article_markdown');
			Cache::delete((string) $entity->id, 'article_summary');
			Cache::delete((string) $entity->id, 'toc_output');

			// waiting to see if we need this
//			debug('link the articles');
//			$entity = $this->manageArticleAssociations($entity);
		}
		return $entity;
	}


	/**
	 * Insure Article is associated with the Topics referenced in it's text body
	 *
	 * Topics are turned into case-insensitive regex patterns. The patterns are
	 * forced to start at word boundaries, so PHP will not be found in CakePHP.
	 * But some topics can be plural and word boundaries are not forced at the
	 * end, so 'design patterns' in the text will be found by the singular
	 * topic entry 'design pattern'. Hence, singular Topics are the prefered.
	 *
	 * @param Entity $entity
	 * @return Entity
	 */
	private function manageTopicAssociations($entity) {
		// get linked topics recorded in the data table
		$topics = $this->Topics->find('list', [
			'keyField' => 'id',
			'valueField' => 'slug'
		]);
		$topics->matching('Articles', function ($q) use ($entity) {
			return $q->where(['Articles.id' => $entity->id]);
		});
		$current_topics = $topics->toArray();

		// Get referenced topics from newly edited article
		//		first make each topic into an regex capture-pattern
		$all_topics = $this->Topics->find('topicList');
		$patterns = $all_topics->map(function($value, $key) {
			return "(\b$value)";
		});
		//		then OR all the regex capture patterns together
		$patterns = implode('|', $patterns->toArray());
		preg_match_all("/$patterns/i", $entity->text, $match);
		//		finally get unique list of slugs for topics that were found
		$match = new Collection($match[0]);
		$topic_keys = array_unique(
			$match->map(function($value, $key) {
				return Slug::generate($value);
			})
			->toArray()
		);

		// determine the differences
		$remove = array_diff($current_topics, $topic_keys); // topics to unlink from the article
		$add = array_diff($topic_keys, $current_topics); // topics to link to the article

		// remove unused links
		foreach ($remove as $topic_id => $target) {
			$unlink_topic = $this->Topics->get($topic_id);
			$this->Topics->unlink($entity, [$unlink_topic]);
		}

		// add newly required links
		foreach ($add as $slug) {
			$topic_entity = $this->Topics->find()->where(['slug' => $slug])->first();
			$entity->topics[] = $topic_entity;
			$entity->dirty('topics', true);
		}

		return $entity;
	}

	private function manageArticleAssociations($entity) {
		return $entity; // we're going to wait and see if this feature is necessary
		preg_match_all($this->article_link_detection_pattern, $entity->text, $match);
		debug($match);
	}

	/**
	 * Insure Article is associated with the Images referenced in it's text body
	 *
	 * Images aren't guaranteed to have unique file names but the image upload
	 * plugin guarantees a uuid folder unique to each image (and it's thumbnail versions)
	 * This feature is leveraged to manage the automatic link management.
	 *
	 * @param Entity $entity
	 * @return Entity
	 */
	private function manageImageAssociations($entity) {
		// Get linked images recorded in data tables
		$images = $this->Images->find('list', [
			'keyField' => 'id',
			'valueField' => 'image_dir'
		]);
		$images->matching('Articles', function ($q) use ($entity) {
			return $q->where(['Articles.id' => $entity->id]);
		});
		$current_links = $images->toArray();

		// Get referenced images from newly edited article
		preg_match_all($this->image_link_detection_pattern, $entity->text, $match);
		$image_keys = $match[1];

		// Determine the differences
		$remove = array_diff($current_links, $image_keys); // images to unlink from the article
		$add = array_diff($image_keys, $current_links); // images to link to the article

		// Removed unused links
		foreach ($remove as $image_id => $target) {
			$unlink_image = $this->Images->get($image_id);
			$this->Images->unlink($entity, [$unlink_image]);
		}

		// Add newly required links
		foreach ($add as $image_dir) {
			$image_entity = $this->Images->find()->where(['image_dir' => $image_dir])->first();
			$entity->images[] = $image_entity;
			$entity->dirty('images', true);
		}
		return $entity;
	}

	/**
	 * Build the TOC anchor points and return-to-toc links
	 *
	 * Every <hx> level is an table of contents entry and needs an anchor.
	 * And each also gets a link to return the user to the TOC.
	 *
	 * Since we're processing new headings here, when done we'll call
	 * for regeneration of the TOC array which is also stored in the table
	 *
	 * Sample result:
	 *  <p class="toc-anchor"><a href="#toc-a-test-article-edited" id="let-s-get-things-rolling">Table of contents</a></p>
	 *	##Let's get things rolling!
	 *
	 * @param object $entity
	 */
	private function manageTocAnchors($entity) {
		$entity->display_text = preg_replace_callback(
			$this->heading_detection_pattern,
			function ($matches) use ($entity) {
				$match = trim($matches[0], "\n\r ");
				// $heading is markdown style heading
				// $slug is slug of heading
				// $return is the id attr of the articles toc block
				list($slug, $return, $heading) = [
					Slug::generate($match),
					Slug::generate('toc-:title', $entity),
					preg_replace('/^(#+).*/', '$1', $match) . preg_replace('/^#+/', '', $match)];
				return sprintf(TOC_LINKBACK, $slug ,$return, $heading);
			},
//				,
			$entity->text
		);
		preg_match_all($this->heading_detection_pattern, $entity->text, $headings);
		$headings = $headings[4];

		$this->buildToc($entity, $headings);
	}

	/**
	 * Build an array to guide table of contents rendering
	 *
	 * The array will contain an entry for the article title (as an h1) and
	 * one entry for every heading in the article. The array is stored
	 * serialized in the db.
	 *
	 * <pre>
	 *	$toc [
	 *		0 => [
	 *			0 => '#', // The hash marks from the markdown heading
	 *			1 => 'Big Time Programming', // The heading text
	 *			2 => 'big-time-programming'] // The heading slug
	 *		],
	 *		1 => [ // the next heading data set ]
	 *	];
	 *
	 * @param object $entity
	 * @param array $headings
	 */
	private function buildToc($entity, $headings = []) {
		if (empty($headings)) {
			preg_match_all($this->heading_detection_pattern, $entity->text, $headings);
			$headings = $headings[4];
		}
		$count = 0;
		$max = count($headings);

		// the regex pattern lost the first # on every heading and captured the trailing newline
		$toc = ['#' . $entity->title];
		while ($count < $max) {
			array_push($toc, '#' . trim($headings[$count++], "\r\n"));
		}
		$toc = new Collection($toc);
		$toc = $toc->map(function ($value, $key) {
			// '##My Title' yeilds '##', 'My Title', 'my-title'
			return [preg_replace('/^(#+).*/', '$1', $value), preg_replace('/^#+/', '', $value), Slug::generate($value)];
		});
		$entity->toc = serialize($toc->toArray());
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
        $this->belongsToMany('Authors', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'author_id',
            'joinTable' => 'articles_authors'
        ]);
//		$this->hasMany('ArticlesImages', [
//			'foreignKey' => 'article_id'
//		]);
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

	/**
	 * Find recent article data sufficient to do lists and summaries
	 *
	 * Filter them by the provided topics if available
	 *
	 * $options keys:
	 *	topic	a topic string (array?),	defaut: all
	 *	limit	# of records to return,		default: 10
	 *	page	for paginated resluts,		default: 1 (unimplemented)
	 *
	 * @param \App\Model\Table\query $query
	 * @param array $options
	 * @return \App\Model\Table\query
	 */
    public function findRecentArticles(query $query, array $options)
    {
        $limit = isset($options['limit']) ? $options['limit'] : 10;
		$page = isset($options['page']) ? $options['page'] : 1;

		$query->select([
			'Articles.id',
			'Articles.publish',
			'Articles.title',
			'Articles.slug',
			])
		->where(['Articles.publish' => 1]);

		if (!empty(Hash::get($options, 'topics._ids'))) {
			$topics = Hash::extract($options, 'topics._ids');
			$query->matching('Topics', function ($q) use ($topics) {
				return $q->where(['Topics.id IN' => $topics]);
			});
		} else {
			$query->limit($limit)
			->select([
				'Articles.published',
			])
			->order(['Articles.modified' => 'DESC']);
		}
		$result = $query->toArray();
		if (key_exists('Filter_Style', $options) && key_exists('topics', $options)) {
			$result = $this->matchAllTopics(
					$result, $options['Filter_Style'], $options['topics']['_ids']);
		}
        return $result;
    }

	/**
	 * Only allow articles that have all the chosen filter topics
	 *
	 * I wonder if there is a pure sql way of doing this
	 *
	 * @param array $articles
	 * @param string $style 'any' or 'all'
	 * @param array $topics
	 * @return array
	 */
	public function matchAllTopics($articles, $style, $topics) {
		if ($style !== 'all') {
			return $articles;
		}
		if (isset($topics[0]) && $topics[0] === '') {
			return $articles;
		}

		// build some data structures to make life easier
		$articlesToScan = new Collection($articles);
		$processedArticles = $articlesToScan->reduce(function($accum, $article){
			$accum['topicSets'][$article->id] = [];
			$accum['articles'][$article->id] = $article;
			return $accum;
		}, ['articles' => [], 'topicSets' => []]);

		// these are our variables
		$indexedArticles = $processedArticles['articles'];
		$topicSets = $processedArticles['topicSets'];
		$articleIds = array_keys($indexedArticles);
		$topicCount = count($topics);

		// we'll work from the join table data
		$this->hasMany('ArticlesTopics');
		$joins = $this->ArticlesTopics->find('all')
				->where(['article_id IN' => $articleIds])
				->where(['topic_id IN' => $topics]);

		$joins = new Collection($joins);
		$result = $joins->reduce(function($accum, $join){
			$accum[$join->article_id][] = $join->topic_id;
			return $accum;
		}, $topicSets);

		// now we can see which article has the right number of topics
		foreach($result as $id => $topicsSeen) {
			if (count($topicsSeen) !== $topicCount) {
				unset($indexedArticles[$id]);
			}
		}
		return $indexedArticles;
	}

}
