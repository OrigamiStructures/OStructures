<ul class="accordion" data-accordion role="tablist">
  <li class="accordion-navigation">
    <a href="#panel1d">Images linked to this article</a>
    <div id="panel1d" class="content active">
        <?= $this->element('Resources/image_resources', ['images' => $articleImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel2d">Images linked to NO articles</a>
    <div id="panel2d" class="content active">
      <?= $this->element('Resources/image_resources', ['images' => $unlinkedImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel3d">Images linked to OTHER articles</a>
    <div id="panel3d" class="content active">
      <?= $this->element('Resources/image_resources', ['images' => $linkedImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel4d"">OTHER articles</a>
    <div id="panel4d" class="content active">
      <?= $this->element('Resources/text_resources', ['articles' => $otherArticles]); ?>
    </div>
  </li>
</ul>
