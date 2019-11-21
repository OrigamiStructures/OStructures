<?php
//debug($images);
foreach ($images as $image) {
    $image_path = 'images/image/' . $image->image_dir . '/' . $image->image;
    $image_dir = $this->Html->image($image_path);
    $markdown_path = explode("\"", $image_dir)[1];
    $markdownImageLink = "![{$image->alt}]({$markdown_path} \"{$image->title}\")";

    echo '<div class="row">';
        echo $this->Html->image($image_path, ['class' => 'resourceImage']);
        echo $this->Html->para('MarkdownImageLink', $markdownImageLink);
    echo '</div>';
}
