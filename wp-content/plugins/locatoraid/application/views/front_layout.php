<?php
$search_label = $this->app_conf->get( 'search_label' );
$search_label = strlen($search_label) ? $search_label : lang('front_address_or_zip');

$search_button = $this->app_conf->get( 'search_button' );
$search_button = strlen($search_button) ? $search_button : lang('common_search');

$autodetect_button = $this->app_conf->get( 'autodetect_button' );
$autodetect_button = strlen($autodetect_button) ? $autodetect_button : lang('front_autodetect');

$your_location_label = $this->app_conf->get( 'your_location_label' );
$your_location_label = strlen($your_location_label) ? $your_location_label : lang('front_current_location');

$is_mobile = FALSE;
if( class_exists('ppUtil') && ppUtil::renderingMobileSite() )
	$is_mobile = TRUE;
?>
<?php echo form_open('search', array('id' => 'lpr-search-form', 'class' => 'form-horizontal form-condensed')); ?>

<ul class="list-unstyled list-margin-v">
	<?php if( $conf_trigger_autodetect ) : ?>
		<li>
			<button type="button" class="btn" id="lpr-autodetect"><?php echo $autodetect_button; ?></button>
		</li>
	<?php endif; ?>

	<li id="lpr-current-location" style="display: none;">
		<ul class="list-inline list-margin-v list-margin-h">
			<li>
				<strong><?php echo $your_location_label; ?></strong> 
			</li>
			<li>
				<button type="button" class="btn" id="lpr-skip-current-location"><?php echo $search_label; ?></button>
			</li>
		</ul>
	</li>

	<li>
		<?php if( $is_mobile ) : ?>
			<?php echo form_hidden( 'country', '' ); ?>
			<?php echo form_hidden( 'search2', '' ); ?>
			<ul class="list-unstyled">
			<li>
				<?php echo form_input( array('name' => 'search', 'style' => 'width: 100%;', 'id' => 'lpr-search-address', 'class' => '', 'placeholder' => $search_label), set_value('search', $search) ); ?>
			</li>
			<li>
				<a href="#" data-role="button" id="lpr-search-button" class="btn"><?php echo $search_button; ?></a>
			</li>
			</ul>
		<?php else : ?>
			<ul class="list-inline list-margin-v list-margin-h">
				<li>
					<?php echo form_input( array('name' => 'search', 'id' => 'lpr-search-address', 'class' => '', 'placeholder' => $search_label), set_value('search', $search) ); ?>
				</li>

				<?php if( (! $is_mobile) && $countries_options ) : ?>
					<li>
						<?php echo form_dropdown( 'country', $countries_options, set_value('country', $country), 'title="' . lang('location_country') . '" id="lpr-countries-dropdown" style="width: 8em;"' ); ?>
					</li>
				<?php else : ?>
					<?php echo form_hidden( 'country', '' ); ?>
				<?php endif; ?>

				<?php if( (! $is_mobile) && $product_options ) : ?>
					<li>
						<?php echo form_dropdown( 'search2', $do_options, set_value('search2', $search2), 'id="lpr-products-dropdown"' ); ?>
					</li>
				<?php else : ?>
					<?php echo form_hidden( 'search2', '' ); ?>
				<?php endif; ?>

				<?php if( (! $is_mobile) && (count($within_options) > 1) ) : ?>
					<li>
						<?php echo form_dropdown( 'within', $dropdown_within, '', 'id="lpr-search-within" class="input-small"' ); ?>
					</li>
				<?php endif; ?>

				<li>
					<a href="#" data-role="button" id="lpr-search-button" class="btn"><?php echo $search_button; ?></a>
				</li>
			</ul>
		<?php endif; ?>
	</li>
</ul>

<?php echo form_close(); ?>

<div id="lpr-results" class="row-fluid">
	<?php if( $show_sidebar ) : ?>
		<div id="lpr-map" class="span8" style="margin-bottom: 2em;"></div>
		<div id="lpr-locations" class="span4"></div>
	<?php else : ?>
		<div id="lpr-map"></div>
	<?php endif; ?>	
	<div class="clearfix"></div>
</div>