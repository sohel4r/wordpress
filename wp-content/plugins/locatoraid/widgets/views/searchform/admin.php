<?php
$label = isset($instance['label']) ? $instance['label'] : __('Address or Zip Code', $this->app);
$btn = isset($instance['btn']) ? $instance['btn'] : __('Search', $this->app);
?>
<p>
	<label for="<?php echo $this->get_field_name('label'); ?>"><?php echo __('Title', $this->app); ?>:</label>
	<input type="text" name="<?php echo $this->get_field_name('label'); ?>" value="<?php echo $label; ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_name('btn'); ?>"><?php echo __('Button Text', $this->app); ?>:</label>
	<input type="text" name="<?php echo $this->get_field_name('btn'); ?>" value="<?php echo $btn; ?>">
</p>