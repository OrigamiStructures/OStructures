<?php
namespace App\Controller;

use App\Controller\ArticlesController;

/**
 * CakePHP BlogArticle
 * @author dondrake
 */
class BlogArticlesController extends ArticlesController {
	
	public function edit($id = null) {
		$this->loadModel('Articles');
        $article = $this->Articles->get($id, [
            'contain' => ['Images', 'Topics']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->data);
//			debug($this->request->data);die;
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));
				if (!$this->request->data['continue']) {
					return $this->redirect(['controller' => 'articles', 'action' => 'index']);
				}				
            } else {
                $this->Flash->error(__('The article could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('article'));
        $this->set('_serialize', ['article']);
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
