<?php
$this->start('script_prepend');
    echo $this->Html->script('../bower_components/modernizr/modernizr.js') . "\n";
    echo $this->Html->script('../bower_components/jquery/dist/jquery.min.js') . "\n";
    echo $this->Html->script('../bower_components/fastclick/lib/fastclick.js') . "\n";
    echo $this->Html->script('../bower_components/foundation/js/foundation.min.js') . "\n";
    
    echo $this->Html->script('app');
$this->end();

$this->prepend('script', $this->fetch('script_prepend'));