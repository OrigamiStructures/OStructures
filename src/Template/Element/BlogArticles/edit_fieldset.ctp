<fieldset>
	<fieldset class="unminimize inner">
		<?= $this->Form->input('heading'); ?>
		<?= $this->Form->input('summary'); ?>
	</fieldset>
	<fieldset class="maximize inner">
		<!-- <?= $this->Form->button(__('Maximize'), ['id' => 'maximize', 'class' => 'enhance button tiny secondary']); ?> -->
		<?= $this->Form->input('text', ['rows' => 50]); ?>
	</fieldset>
</fieldset>
