<?php if( $message ) : ?>
<div class="hc-info-message"><?php echo $message;?></div>
<?php endif; ?>

<?php if( isset($include) ) : ?>
<?php echo $this->load->view($include); ?>
<?php endif; ?>