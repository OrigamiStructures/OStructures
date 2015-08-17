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

		if (!$entity->dirty('text') && $entity->dirty('title')) {
			$this->buildToc($entity);
		}
        if ($entity->isNew() || $entity->dirty('text')) {
			$this->manageTocAnchors($entity);

			$entity = $this->manageImageAssociations($entity);
			debug('setup the topics');
		}
		return $entity;
	}
	
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
		preg_match_all('/!\[.*\/([a-f0-9\-]{36})\//', $entity->text, $match);
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
	 * @param object $entity
	 */
	private function manageTocAnchors($entity) {
		// limitation in the use of capture blocks as function arguments 
		// force this process to be done in two stages. 
		// "=====" should be Slug::generate('$4') but that doesn't work
		$stub_toc_anchors = preg_replace(
				$this->heading_detection_pattern, 
//					'<span class="anchor" id="=====' . "\"></span>\n$1", 
				'<span class="anchor" id="====="><a href="#' . Slug::generate('toc-:title', $entity) . "\">Table of contents</a></span>\n#$4", 
				$entity->text
		);

		preg_match_all($this->heading_detection_pattern, $entity->text, $headings);
		$headings = $headings[4];
		$text = explode('=====', $stub_toc_anchors);
		$max = count($text) - 1;
		$count = 0;
		$entity->display_text = '';
		
		while ($count < $max) {
			$entity->display_text .= $text[$count] . Slug::generate($headings[$count++]);
		}
		$entity->display_text .= $text[$count];
		
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
}
