<?php
namespace App\Controller;

use App\Controller\ArticlesController;

/**
 * CakePHP BlogArticle
 * @author dondrake
 */
class BlogArticlesController extends ArticlesController {
	
	public $useTable = 'articles';

	public function edit($id = null) {
		$this->loadModel('Articles');
//		$this->BlogArticles = $this->Articles;
		parent::edit($id);
		
	}
}
