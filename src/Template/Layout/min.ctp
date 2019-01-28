<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
echo $this->element('Common/common_scripts');
if ($this->request->action == 'edit');
if (isset($article)) {
	$this->start('title');
	echo ($this->request->action == 'edit' ? 'E:':'') . $article['title'];
	$this->end();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('main.css') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div class="inner-wrap">
        <title>Origami Structures: PHP Development and developer assistance</title>
        <meta content="Evolving thoughts on PHP development, focused on CakePHP v3." name="description">
        <?= $this->element('Common/header'); ?>
        <?= $this->fetch('content') ?>
        <?= $this->element('Common/footer'); ?>
    </div>
</body>
</html>
