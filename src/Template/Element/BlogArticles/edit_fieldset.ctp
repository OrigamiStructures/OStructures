<fieldset>
	<fieldset class="unminimize inner">
		<?= $this->Form->input('heading'); ?>
		<?= $this->Form->input('summary', ['rows' => 'auto']); ?>
		<?= $this->Form->input('topics'); ?>
	</fieldset>
	<fieldset class="maximize inner">
		<!-- <?= $this->Form->button(__('Maximize'), ['id' => 'maximize', 'class' => 'enhance button tiny secondary']); ?> -->
		<?= $this->Form->input('text', ['rows' => 'auto']); ?>
	</fieldset>
</fieldset>