<?php
$fields = array(
	array(
		'name' 		=> 'default_search',
 		'title'		=> lang('conf_default_search'),
		'type'		=> 'text',
		'help'		=> lang('conf_default_search_help'),
		),
	array(
		'name' 		=> 'append_search',
 		'title'		=> lang('conf_append_search'),
		'type'		=> 'text',
		'help'		=> lang('conf_append_search_help'),
		),
	array(
		'name' 		=> 'start_listing',
 		'title'		=> lang('conf_start_listing'),
		'type'		=> 'dropdown',
		'options'	=> array(
			0	=> lang('common_no'),
			1	=> lang('common_yes'),
			),
		),
	array(
		'name' 		=> 'measurement',
 		'title'		=> lang('conf_measurement'),
		'type'		=> 'dropdown',
		'options'	=> array(
			'miles'	=> lang('conf_measurement_miles'),
			'km'	=> lang('conf_measurement_km'),
			),
		),
	array(
		'name' 		=> 'search_within',
 		'title'		=> lang('conf_search_within') . ', ' . $defaults['measurement'],
		'type'		=> 'text',
		'help'		=> lang('conf_search_within_help'),
		),
	array(
		'name' 		=> 'search_label',
 		'title'		=> lang('conf_search_label'),
		'type'		=> 'text',
		),
	array(
		'name' 		=> 'search_button',
 		'title'		=> lang('conf_search_button'),
		'type'		=> 'text',
		),
	array(
		'name' 		=> 'autodetect_button',
 		'title'		=> lang('conf_autodetect_button'),
		'type'		=> 'text',
		),
	array(
		'name' 		=> 'your_location_label',
 		'title'		=> lang('conf_your_location_label'),
		'type'		=> 'text',
		),
	array(
		'name' 		=> 'not_found_text',
 		'title'		=> lang('conf_not_found_text'),
		'type'		=> 'textarea',
		),
	array(
		'name' 		=> 'show_sidebar',
 		'title'		=> lang('conf_show_sidebar'),
		'type'		=> 'checkbox',
		),
	array(
		'name' 		=> 'show_distance',
 		'title'		=> lang('conf_show_distance'),
		'type'		=> 'checkbox',
		),
	array(
		'name' 		=> 'group_output',
 		'title'		=> lang('conf_group_output'),
		'type'		=> 'dropdown',
		'options'	=> array(
			''				=> lang('common_none'),
			'state'			=> lang('conf_group_output_state'),
			'state_city'	=> lang('conf_group_output_state_city'),
			'city'			=> lang('conf_group_output_city'),
			'alphabetical'	=> lang('conf_group_output_alphabetical'),
			),
		),
	array(
		'name' 		=> 'choose_country',
 		'title'		=> lang('conf_choose_country'),
		'type'		=> 'dropdown',
		'options'	=> array(
			0	=> lang('common_no'),
			1	=> lang('common_yes'),
			),
		),
	array(
		'name' 		=> 'limit_output',
 		'title'		=> lang('conf_limit_output'),
		'type'		=> 'text',
		'help'		=> lang('conf_limit_output_help')
		),
	array(
		'name' 		=> 'trigger_autodetect',
 		'title'		=> lang('conf_trigger_autodetect'),
//		'type'		=> 'checkbox',
		'type'		=> 'dropdown',
		'options'	=> array(
			0			=> lang('common_no'),
			1			=> lang('common_automatically'),
			2			=> lang('common_let_user_select')
			),
		),
	array(
		'name' 		=> 'csv_separator',
 		'title'		=> lang('conf_csv_separator'),
		'type'		=> 'dropdown',
		'options'	=> array(
			','	=> ',',
			';'	=> ';',
			),
		),
	);

/* get languages */
$languages = hc_list_subfolders( APPPATH . '/language' );
if( count($languages) > 1 )
{
	$language_options = array();
	foreach( $languages as $lng )
	{
		$language_options[ $lng ] = $lng;
	}

	array_unshift( $fields,
		array(
			'name' 		=> 'language',
			'title'		=> lang('common_language'),
			'type'		=> 'dropdown',
			'options'	=> $language_options
			)
		);
}

reset( $fields );
?>

<div class="page-header">
<h2><?php echo lang('menu_conf_settings');?></h2>
</div>

<?php echo form_open('', array('class' => 'form-horizontal form-condensed')); ?>

<?php foreach( $fields as $f ) : ?>
	<?php
	$error = form_error($f['name']);
	$class = $error ? ' error' : '';

	$skip_me = FALSE;
	switch( $f['name'] )
	{
		case 'choose_country':
			$lm = new Location_model;
			if( $countries = $lm->get_countries() )
			{
				$f['help'] = join( ', ', $countries );
			}
			else
			{
				$skip_me = TRUE;
			}
			break;
	}

	if( $skip_me )
		continue;
	?>
	<div class="control-group<?php echo $class; ?>">
		<label class="control-label" for="<?php echo $f['name']; ?>"><?php echo $f['title'];?></label>

		<div class="controls">
			<?php
			switch( $f['type'] )
			{
				case 'dropdown':
					echo form_dropdown($f['name'], $f['options'], set_value($f['name'], $defaults[$f['name']]));
					break;
				case 'checkbox':
					echo form_checkbox($f['name'], 1, set_checkbox($f['name'], 1, $defaults[$f['name']] ? TRUE : FALSE) );
					break;
				case 'textarea':
					echo form_textarea( $f['name'], set_value($f['name'], $defaults[$f['name']]), 'style="width: 20em; height: 8em;"' );
					break;
				default:
					echo form_input($f, set_value($f['name'], $defaults[$f['name']]));
					break;
			}
			?>
			<?php if( isset($f['help']) ) : ?>
				<span class="help-block"><?php echo $f['help']; ?></span>
			<?php endif; ?>

			<?php if( $error ) : ?>
				<span class="help-inline"><?php echo $error; ?></span>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>

<?php
$products = $this->app_conf->get('products');
?>
<?php if( $products ) : ?>
	<div class="control-group<?php echo $class; ?>">
		<label class="control-label"></label>
		<div class="controls">
			<?php echo ci_anchor( 'admin/conf/resetproducts', 'Reset Products' ); ?>
		</div>
	</div>
<?php endif; ?>

<div class="controls">
<?php echo form_submit( array('name' => 'submit', 'class' => 'btn btn-primary'), lang('common_save'));?>
</div>

<?php echo form_close();?>
