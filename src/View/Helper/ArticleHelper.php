<?php
namespace App\View\Helper;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Cake\View\Helper;

/**
 * CakePHP ArticleHelper
 * @author dondrake
 */
class ArticleHelper extends Helper {
	
	public $helpers = ['Html'];

	public function editLink($article) {
        return $this->Html->link('Edit', ['action' => 'edit', $article->id]) . ' || ';
//
//        Likely bad security process removed JT 2018.11.28 1:54 PM
//
//        return preg_match('/^d(ev)*\./', strtolower($this->request->env('HTTP_HOST'))) ?
//            $this->Html->link('Edit', ['action' => 'edit', $article->id]) . ' || ' :
//			'';
	}
}
