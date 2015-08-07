<?php
//debug($images);
foreach ($images as $image) {
//	debug($image);
	echo $this->Html->image('images/image/' . $image->image_dir . '/' . $image->image);
}