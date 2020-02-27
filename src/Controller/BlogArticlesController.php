<?php
namespace App\Controller;

use App\Controller\ArticlesController;
use Cake\Collection\Collection;
use App\Model\Entity\BlogArticle;
use Cake\Log\Log;
use App\Lib\GitRepo;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * CakePHP BlogArticle
 * @author dondrake
 */
class BlogArticlesController extends ArticlesController {


    public $paginate = [
        'limit' => 10,
        'order' => [
            'Articles.published' => 'desc'
        ]
    ];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

	public function add() {
		$article = new BlogArticle(['publish' => 1]);
		$this->BlogArticles->save($article);
		$this->redirect('/blog_articles/edit/' . $article->id );
	}

    /**
     * Generate dynamic page showing articles on Topics
     */
    public function portal()
    {
        $TopicsTable = TableRegistry::getTableLocator()->get('Topics');
        $topicIds = $this->request->getData('topics._ids');
        $q = $TopicsTable->find('all')
            ->where(['id IN' => $topicIds])
            ->contain(['Articles' => [
                'fields' => ['id', 'slug', 'title', 'text'],
                'Topics' => ['fields' => ['id', 'name']]
            ]]);
        //reduce q result to distinct articles with topic counts
        $articles = $filterTopics = [];
        foreach ($q->toArray() as $topicAndArticles) {
            $filterTopics[] = $topicAndArticles->name;
            $articlesOnTopic = collection($topicAndArticles->articles);
            $distinctArticles = $articlesOnTopic->reduce(function ($reduced, $entity) {
                if (!isset($reduced[$entity->id])) {
                    unset($entity->_joinData);
                    $entity->topics = count($entity->topics);
                    $entity->clean();
                    $reduced[$entity->id] = $entity;
                }
                return $reduced;
            }, $articles);
        }
        //sort articles based on topic counts
        usort($distinctArticles, function($thisOne, $otherOne) {
            return $thisOne->topics <=> $otherOne->topics;
        });
        $this->set(compact('filterTopics', 'distinctArticles'));
        // stuff to support a normal article page
        $this->layout = 'min';
        $this->sidebarData();
	}

	public function edit($id = null) {
		$this->layout = 'min';
        try {
            $article = $this->{$this->modelClass}->get($id, [
                'contain' => ['Images', 'Topics', 'Authors']
            ]);
        } catch (Exception $exc) {
            $article = [];
            echo $exc->getTraceAsString();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

			/*
			 * Can't figure out how to get the authors data to
			 * patch into the entity properly. So, I'm gonna jam
			 * it in manually. First an early attemp to patch:
			 */
			// adjust author id values
			// input name attributes can't be configured exactly right
//			if (is_array($this->request->data['authors'])) {
//				$authors = [];
//				foreach ($this->request->data['authors'] as $author_id) {
//					$authors[]= ['author_id' => $author_id];
//				}
//				$this->request->data['authors'] = $authors;
//			}
			/*
			 * Now the big hammer
			 */
			if (is_array($this->request->data['authors'])) {
				$chosen_authors = Hash::extract($this->request->data, 'authors.{n}');
//				osd($chosen_authors);
				$authors = [];
				foreach ($article->authors as $author) {
					$authors[]= $author->id;
				}
				foreach (array_diff($chosen_authors, $authors) as $new_auth) {
					$entity = new \App\Model\Entity\Author(['id' => $new_auth]);
					array_push($article->authors, $entity);
				}
				$article->dirty('authors', true);
			}

            $article = $this->{$this->modelClass}
					->patchEntity($article, $this->request->getData(), ['associated' => ['Authors']]);

			if ($this->{$this->modelClass}->save($article)) {
//				GitRepo::write($article);
                $this->Flash->success(__('The article has been saved.'));
				if (!$this->request->data['continue']) {
					return $this->redirect([
						'controller' => 'BlogArticles',
						'action' => 'view',
						$article->slug]);
				}
            } else {
                $this->Flash->error(__('The article could not be saved. '
						. 'Please, try again.'));
            }
        }

        try {
            $article = $this->{$this->modelClass}->get($id, [
                'contain' => ['Images', 'Topics', 'Authors']
            ]);
            $articleImages = new Collection($article->images);
            $Images = $this->{$this->modelClass}->Images->find('all');
            $Images->contain([$this->modelClass]);
            $unlinkedImages = $this->unlinkedImages($Images);
            $linkedImages = $this->linkedImages($Images, $id);
            $otherArticles = $this->otherArticles($id);
			$this->sidebarData();

			// make the options list for author select input
			$authors = $this->{$this->modelClass}->Authors->find('list');
			// make the val option for author select (to show current stored values)
			$auth = new Collection($article->authors);
			$author_val = $auth->reduce(function($accumulated, $author) {
				$accumulated[] = $author->id;
				return $accumulated;
			}, []);

        } catch (Exception $exc) {
            $article = $articleImages = $unlinkedImages = $linkedImages = array();
            echo $exc->getTraceAsString();
        }

		$toc = $article->toc();

        $this->set(compact('article', 'articleImages', 'unlinkedImages', 'linkedImages', 'toc', 'otherArticles', 'authors', 'author_val'));
        $this->set('_serialize', ['article']);
	}

	public function index() {
        $this->layout = 'min';
        try {
            $query = $this->{$this->modelClass}->find('all')
                    ->contain([])
                    ->where(['publish' => 1])
					->order(['modified' => 'DESC']);
            $this->sidebarData();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        $articles = $this->paginate($query);
        $this->set(compact('articles'));
        $this->set('_serialize', ['articles']);
    }

	public function view($id = NULL) {
		$slug = $id;
        $this->layout = 'min';
        try {
            $article = $this->{$this->modelClass}->find()
                    ->where(["{$this->modelClass}.slug" => $slug])
                    ->contain(['Authors', 'Topics'])
                    ->first();
			$toc = $article->toc();
			$this->sidebarData();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        $this->set(compact('article','toc'));
        $this->set('_serialize', ['article']);
	}

    /**
     * Filter the provided $Images object to return those not linked to any article
     *
     * @param object $Images
     * @return object the unlinked images as a Collection Object
     */
    private function unlinkedImages($Images) {
        return $Images->filter(function($image, $key){
                return count($image->articles) == 0;
            });
    }

    /**
     * Filter the provided $Images object to return those only linked to OTHER articles
     *
     * @param object $Images
     * @param string $id the current article id
     * @return object the linked images as a Collection Object
     */
    private function linkedImages($Images, $id) {
        $test_array = [];
        return $Images->filter(function($image, $key) use ($id, &$test_array){
            $test_array[$image->id] = [];
            $imageId = $image->id;
//			debug($image->toArray());
            $articles = New Collection($image->blog_articles);
            $articles = $articles->each(function($value, $key) use (&$test_array, $imageId){
                $test_array[$imageId][$value->id] = $value->id;
            });
            if(in_array($id, $test_array[$imageId]) || empty($test_array[$imageId])){
                return FALSE;
            } else {
                return TRUE;
            }
        });
    }

    private function otherArticles($id) {
        try {
            $otherArticles = $this->{$this->modelClass}->find('all');
            $otherArticles->where([
                'id !=' => $id]);
        } catch (Exception $e) {

        }
        return $otherArticles;
    }

    /**
     * Fetch data for the sidebar
     *
     * This function MUST be called in a try block
     */
    private function sidebarData() {
        $this->loadModel('Articles');

		$recent = $this->Articles->find('recentArticles', $this->request->data);

        $topics = $this->{$this->modelClass}->Topics->find('topicList');
        $this->set(compact('recent', 'topics'));
    }

}
