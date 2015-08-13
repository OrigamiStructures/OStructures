<?php
namespace App\Controller;

use App\Controller\ArticlesController;
use Cake\Collection\Collection;
use Cake\Log\Log;

/**
 * CakePHP BlogArticle
 * @author dondrake
 */
class BlogArticlesController extends ArticlesController {
	
	public function edit($id = null) {
		$this->loadModel('Articles');
        try {
            $article = $this->Articles->get($id, [
                'contain' => ['Images', 'Topics']
            ]);
//		debug($article->toc());
            $articleImages = new Collection($article->images);
            $Images = $this->Articles->Images->find('all');
            $Images->contain(['Articles']);
            $unlinkedImages = $this->unlinkedImages($Images);
            $linkedImages = $this->linkedImages($Images, $id);
        } catch (Exception $exc) {
            $article = $articleImages = $unlinkedImages = $linkedImages = array();
            echo $exc->getTraceAsString();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->data);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));
				if (!$this->request->data['continue']) {
					return $this->redirect(['controller' => 'articles', 'action' => 'index']);
				}				
            } else {
                $this->Flash->error(__('The article could not be saved. Please, try again.'));
            }
        }
		$toc = $article->toc();
        $this->set(compact('article', 'articleImages', 'unlinkedImages', 'linkedImages', 'toc'));
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
            $articles = New Collection($image->articles);
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
	
	public function add() {
		$this->loadModel('Articles');
		parent::add();
	}

	public function index() {
		$this->loadModel('Articles');
		parent::index();
		$this->render('/Articles/index');
	}

}
