<!-- Common/topic_filters -->
<?php
if (isset($topics)) :
	$submitFilterButton = $submitPortalButton = '';
	if ($this->request->action !== 'edit') {
		echo $this->Form->create();
        $submitFilterButton = $this->Form->submit('Filter', ['class' => 'button tiny radius secondary']);
        $submitPortalButton = $this->Form->submit('Portal', [
            'class' => 'button tiny radius secondary',
            'formaction' => \Cake\Routing\Router::url(['controller' => 'blog_articles', 'action' => 'portal'])
        ]);
	}
	$topics = $topics->toArray();
	echo $this->Form->fieldset(
			$this->Form->input('topics._ids', ['options' => $topics, 'label' => FALSE, 'empty' => 'All'])
			. '<br />' . $this->Form->radio('Filter Style', [
				['value' => 'all', 'text' => ' Must have all topics', 'checked' => TRUE],
				['value' => 'any', 'text' => ' Can have any of the topcis']
			])
			. $submitFilterButton . $submitPortalButton,
			['legend' => 'Filter by topic']) . "\n";
	if ($this->request->action !== 'edit') {
		echo $this->Form->end();
	}
endif;
?>
<!-- End Common/topic_filters -->
