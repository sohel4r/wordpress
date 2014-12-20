<?php
$fields = array(
	array('name' => 'email'),
	array('name' => 'password', 'type' => 'password'),
	array('name' => 'password2', 'type' => 'password'),
	);
reset( $fields );
?>

<h2><?php echo lang('setup_title');?></h2>

<?php echo form_open('setup/run', array('class' => '')); ?>
<?php foreach( $fields as $f ) : ?>
<p>
<?php echo form_error($f['name']); ?>
<label for="<?php echo $f['name']; ?>"><?php echo lang('setup_' . $f['name']);?></label>
<?php echo form_input($f, set_value($f['name'])); ?>
</p>
<?php endforeach; ?>

<div class="controls">
<?php echo form_button( array('type' => 'submit', 'name' => 'submit', 'class' => 'btn btn-primary'), lang('setup_setup')); ?>
</div>

<?php echo form_close();?>

<?php $this->load->view('footer'); ?>
