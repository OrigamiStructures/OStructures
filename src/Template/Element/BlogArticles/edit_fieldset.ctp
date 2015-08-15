<fieldset>
	<fieldset class="unminimize inner">
		<?= $this->Form->input('title'); ?>
		<?= $this->Form->input('summary', ['rows' => 'auto']); ?>
	</fieldset>
	<fieldset class="maximize inner">
		<!-- <?= $this->Form->button(__('Maximize'), ['id' => 'maximize', 'class' => 'enhance button tiny secondary']); ?> -->
		<?= $this->Form->input('text', ['rows' => 25]); ?>
	</fieldset>
</fieldset>
