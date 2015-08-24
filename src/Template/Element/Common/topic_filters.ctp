<!-- Common/topic_filters -->
<?php
if (isset($topics)) :
	echo $this->Form->create();
	$topics = $topics->toArray();
	echo $this->Form->fieldset(
			$this->Form->input('topics._ids', ['options' => $topics, 'label' => FALSE, 'empty' => 'All'])
			. $this->Form->submit('Filter', ['class' => 'button tiny radius secondary']),
			['legend' => 'Filter by topic']) . "\n";
	echo $this->end();
endif;
?>
<!-- End Common/topic_filters -->
