<?php echo form_open('search', array('id' => 'lpr-search-form', 'class' => 'form-horizontal form-condensed')); ?>

<div class="control-group">
	<label class="control-label" style="text-align: left;" for="search">
		<?php echo lang('front_address_or_zip'); ?>
	</label>
	<div class="controls">
		<?php echo form_input( array('name' => 'search', 'id' => 'lpr-search-address', 'class' => 'input-medium'), set_value('search', $search) ); ?>
		<div id="lpr-current-location" style="display: none;">
			<strong><?php echo lang('front_current_location'); ?></strong> 
			<a href="#" id="lpr-skip-current-location"><?php echo lang('front_enter_address'); ?></a>
		</div>
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<a href="#" id="lpr-autodetect"><?php echo lang('front_autodetect'); ?></a>
	</div>

	<?php if( count($within_options) > 1 ) : ?>
		<label class="control-label" style="text-align: left;" for="within">
			<?php echo lang('front_search_within'); ?>
		</label>
		<div class="controls">
			<?php echo form_dropdown( 'within', $dropdown_within, '', 'id="lpr-search-within" class="input-small"' ); ?> <?php echo $measure_title; ?>
		</div>
	<?php endif; ?>

	<div class="controls" id="lpr-search-controls">
		<?php if( ! $product_options ) : ?>
			<?php echo form_button( array('name' => 'submit', 'type' => 'submit', 'class' => 'btn', 'id' => 'lpr-search-button'), lang('common_search'));?>
			<?php echo form_hidden( 'search2', '' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php if( $product_options ) : ?>
	<div class="control-group">
		<label style="text-align: left;" class="control-label" for="search2"><?php echo $products_label; ?></label>
		<div class="controls">
			<?php echo form_dropdown( 'search2', $do_options, set_value('search2', $search2) ); ?>
		</div>
	</div>
	<div class="controls" id="lpr-search-controls">
		<?php echo form_button( array('name' => 'submit', 'type' => 'submit', 'class' => 'btn', 'id' => 'lpr-search-button'), lang('common_search'));?>
	</div>
<?php endif; ?>

<?php echo form_close(); ?>

<div id="lpr-results" class="row-fluid" style="width: 90%; border: #ccc 1px solid;">
	<?php if( $show_sidebar ) : ?>
		<div id="lpr-map" class="span8"></div>
		<div id="lpr-locations" class="span4"></div>
	<?php else : ?>
		<div id="lpr-map"></div>
	<?php endif; ?>	
	<div class="clearfix"></div>
</div>