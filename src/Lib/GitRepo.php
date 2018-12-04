<?php
namespace App\Lib;

use Cake\Filesystem\File;
use Cake\Core\Configure;

/**
 * WriteBackups stores artices in a remote git repo so I'll have a change history
 * 
 * The path to the repo will be in a config written from app.php
 *
 * @author dondrake
 */
class GitRepo {
	
	public static $file;


	public static function write($article) {
		self::writeMarkdown($article);
		self::writeEntity($article);
		self::commit($article);
		return;
	}
	
	protected static function writeMarkdown($article) {
		$dir = Configure::read('repoPath.markdown');
		$name = "article-$article->id";
		$path = $dir.$name;
		$markdown = "# $article->title\n\n$article->text";
		
		self::_write($path, $markdown);
		return;
	}
	
	protected static function writeEntity($article) {
		$dir = Configure::read('repoPath.entity');
		$name = "article-$article->id";
		$path = $dir.$name;
		$content = serialize($article);
		
		self::_write($path, $content);
		return;
	}
	
	protected static function _write($path, $content) {
		self::$file = New File($path, TRUE, '775');
		self::$file->open('w');
		$result = self::$file->write($content);
		self::$file->close();
		return;
	}
	
	protected static function commit($article) {
		$path = Configure::read('repoPath.command');
		$content = 
"cd /Library/WebServer/Documents/g 2>&1
git add -A 2>&1
git commit -m '$article->slug' 2>&1
";
		
		self::_write($path, $content);
		
		$commands = [
			'sh /Library/WebServer/Documents/g/commit.bash 2>&1',
		];
		
		foreach($commands as $command) {
			shell_exec($command);
		}
		
		return;
	}
}
