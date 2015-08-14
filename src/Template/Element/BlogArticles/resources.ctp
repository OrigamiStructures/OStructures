<ul class="accordion" data-accordion role="tablist">
  <li class="accordion-navigation">
    <a href="#panel1d" role="tab" id="panel1d-heading" aria-controls="panel1d">Images linked to this article</a>
    <div id="panel1d" class="content active" role="tabpanel" aria-labelledby="panel1d-heading">
        <?= $this->element('Resources/image_resources', ['images' => $articleImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel2d"  role="tab" id="panel2d-heading" aria-controls="panel2d">Images linked to NO articles</a>
    <div id="panel2d" class="content active" role="tabpanel" aria-labelledby="panel2d-heading">
      <?= $this->element('Resources/image_resources', ['images' => $unlinkedImages]); ?>
    </div>
  </li>
  <li class="accordion-navigation">
    <a href="#panel3d" role="tab" id="panel3d-heading" aria-controls="panel3d">Images linked to OTHER articles</a>
    <div id="panel3d" class="content active" role="tabpanel" aria-labelledby="panel3d-heading">
      <?= $this->element('Resources/image_resources', ['images' => $linkedImages]); ?>
    </div>
  </li>
</ul>
