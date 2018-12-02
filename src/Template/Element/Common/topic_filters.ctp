<!-- Common/topic_filters -->
<?php
if (isset($topics)) :
	$submitFilterButton = '';
	if ($this->request->action !== 'edit') {
		echo $this->Form->create();
		$submitFilterButton = $this->Form->submit('Filter', ['class' => 'button tiny radius secondary']);
	}
	$topics = $topics->toArray();
	echo $this->Form->fieldset(
			$this->Form->input('topics._ids', ['options' => $topics, 'label' => FALSE, 'empty' => 'All'])
			. '<br />' . $this->Form->radio('Filter Style', [
				['value' => 'all', 'text' => ' Must have all topics', 'checked' => TRUE],
				['value' => 'any', 'text' => ' Can have any of the topcis']
			])
			. $submitFilterButton,
			['legend' => 'Filter by topic']) . "\n";
	if ($this->request->action !== 'edit') {
		echo $this->Form->end();
	}	
endif;
?>
<!-- End Common/topic_filters -->
