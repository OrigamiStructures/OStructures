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
		parent::edit($id);
		
	}
	
	public function add() {
		$this->loadModel('Articles');
		parent::add();
	}

}
