<?php
//debug($images);
foreach ($images as $image) {
//	debug($image);
    $image_path = 'images/image/' . $image->image_dir . '/' . $image->image;
	echo $this->Html->image($image_path);
    $markdownImageLink = "![{$image->alt}]({$image_path} \"{$image->title}\")";
	echo $this->Html->para('MarkdownImageLink', $markdownImageLink);
}