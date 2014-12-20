<?php if( $message ) : ?>
<div class="alert alert-info">
<?php	if( is_array($message) ) : ?>
<ul>
<?php 		foreach( $message as $m ) : ?>
<li><?php 		echo $m; ?></li>
<?php 		endforeach; ?>
</ul>
<?php 	else : ?>
<?php 		echo $message;?>
<?php 	endif; ?>
</div>
<?php endif; ?>

<?php if( isset($include) ) : ?>
<?php echo $this->load->view($include); ?>
<?php endif; ?>

<?php
exit;
?>