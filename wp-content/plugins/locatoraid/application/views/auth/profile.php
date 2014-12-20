<h2>Profile</h2>

<?php echo form_open('', array('class' => 'form-horizontal')); ?>

<?php
$error = form_error($email['name']);
$class = $error ? 'control-group error' : 'control-group';
?>
<div class="<?php echo $class; ?>">
<label class="control-label" for="<?php echo $email['name']; ?>"><?php echo lang('common_email'); ?></label>
<div class="controls">  
<?php echo form_input( $email, set_value($email['name'], $defaults[$email['name']]) ); ?>
<?php if( $error ) : ?>
<span class="help-inline"><?php echo $error; ?></span>
<?php endif; ?>
</div>  
</div>

<div class="controls">
<h4>If Changing Password</h4>
</div>

<?php
$error = form_error($new_password['name']);
$class = $error ? 'control-group error' : 'control-group';
?>

<div class="<?php echo $class; ?>">
<label class="control-label" for="<?php echo $new_password['name']; ?>"><?php echo lang('common_new_password'); ?></label>
<div class="controls">  
<?php echo form_input( $new_password ); ?>
<?php if( $error ) : ?>
<span class="help-inline"><?php echo $error; ?></span>
<?php endif; ?>
</div>  
</div>

<?php
$error = form_error($new_password_confirm['name']);
$class = $error ? ' error' : '';
?>

<div class="control-group<?php echo $class; ?>">
<label class="control-label" for="<?php echo $new_password_confirm['name']; ?>"><?php echo lang('common_new_password_confirm'); ?></label>
<div class="controls">
<?php echo form_input( $new_password_confirm ); ?>
<?php if( $error ) : ?>
<span class="help-inline"><?php echo $error; ?></span>
<?php endif; ?>
</div>  
</div>

<div class="controls">
<?php echo form_submit( array('name' => 'submit', 'class' => 'btn btn-primary'), lang('common_save'));?>
</div>

<?php echo form_close();?>