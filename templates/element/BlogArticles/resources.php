<ul class="accordion" data-accordion role="tablist">
  <li class="accordion-navigation">
    <a href="#panel1d">Current Images</a>
    <div id="panel1d" class="content active">
        <?= $this->element('Resources/image_resources', ['images' => $articleImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel2d">Unused Images</a>
    <div id="panel2d" class="content active">
      <?= $this->element('Resources/image_resources', ['images' => $unlinkedImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel3d">Used Images</a>
    <div id="panel3d" class="content active">
      <?= $this->element('Resources/image_resources', ['images' => $linkedImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel4d">Articles</a>
    <div id="panel4d" class="content active">
      <?= $this->element('Resources/text_resources', ['articles' => $otherArticles]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel5d">Portals</a>
    <div id="panel5d" class="content active">
      <?= $this->element('Resources/portal_resources', ['articles' => $recent]); ?>
    </div>
  </li>
</ul>
