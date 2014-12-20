<?php
$fields = $this->fields;
reset( $fields );
?>

<div class="page-header">
<h2><?php echo lang('location_add');?></h2>
</div>

<?php echo form_open('', array('class' => 'form-horizontal form-condensed')); ?>

<?php foreach( $fields as $f ) : ?>
<?php
$error = form_error($f['name']);
$class = $error ? 'control-group error' : 'control-group';
$type = isset($f['type']) ? $f['type'] : '';
$required = isset($f['required']) ? $f['required'] : FALSE;
if( $f['name'] == 'website' )
{
	$f['title'] = lang('location_website');
}
?>
<div class="<?php echo $class; ?>">

<label class="control-label" for="<?php echo $f['name']; ?>">
<?php echo $f['title']; ?><?php if( $required ){echo ' *';}; ?>
<?php if($f['name'] == 'products') : ?>
<span class="help-block">(<?php echo lang('location_products_field_help'); ?>)</span>
<?php endif; ?>
</label>

<div class="controls">  
<?php 
switch( $type )
{
	case 'dropdown':
		echo form_dropdown( $f['name'], $f['options'], set_value($f['name']) );
		break;
	case 'textarea':
		echo form_textarea( $f, set_value($f['name']) );
		break;
	default:
		echo form_input( $f, set_value($f['name']) );
		break;
}
?>
<?php if( $error ) : ?>
<span class="help-inline"><?php echo $error; ?></span>
<?php endif; ?>
</div>  
</div>

<?php endforeach; ?>

<div class="controls">
<?php echo form_submit( array('name' => 'submit', 'class' => 'btn btn-primary'), lang('common_add')); ?>
</div>

<?php echo form_close();?>
