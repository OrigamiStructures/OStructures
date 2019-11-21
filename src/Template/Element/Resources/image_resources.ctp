<?php
//debug($images);
/* @var array $images */
/* @var \App\Model\Entity\Image $image */
foreach ($images as $image) {
    $image_path = 'images/image/' . $image->image_dir . '/' . $image->image;
    $image_dir = $this->Html->image($image_path);
    $markdown_path = explode("\"", $image_dir)[1];
    $markdownImageLink = "![{$image->alt}]({$markdown_path} \"{$image->title}\")";

    echo '<div class="row">';
    if (!isset($preview)) {
        echo $this->Html->image($image_path, ['class' => 'resourceImage']);
    }
    echo $this->Html->para('MarkdownImageLink', $markdownImageLink);
    echo '</div>';
}
