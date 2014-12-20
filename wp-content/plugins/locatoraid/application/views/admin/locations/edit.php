<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<?php
$fields = $this->fields;
array_unshift( $fields, array(
	'name'		=> 'id',
	'title'		=> 'ID',
	'size'		=> 4,
	'readonly'	=> 'readonly',
	'style' 	=> 'width: 4em;'
	));
reset( $fields );

if( ($object['latitude'] == -1) && ($object['longitude'] == -1) )
{
	$geocoded = -1;
}
elseif( ($object['latitude'] == 0) && ($object['longitude'] == 0) )
{
	$geocoded = 0;
}
else
{
	$geocoded = 1;
}
?>

<div class="page-header">
<h2><?php echo lang('location_edit');?></h2>
</div>

<div class="row-fluid">

	<div class="span6">
		<?php echo form_open('', array('class' => 'form-horizontal form-condensed')); ?>

		<?php foreach( $fields as $f ) : ?>
			<?php
			$error = form_error($f['name']);
			$class = $error ? 'error' : '';
			$type = isset($f['type']) ? $f['type'] : '';
			$required = isset($f['required']) ? $f['required'] : FALSE;
			if( $f['name'] == 'website' )
			{
				$f['title'] = lang('location_website');
			}
			?>
			<div class="control-group <?php echo $class; ?>">
				<?php if( $f['name'] == 'misc1' ) : ?>
					<span class="help-inline">
					<em>You can place an image URL in any of the misc fields and it will be rendered as image</em>
					</span>
				<?php endif; ?>

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
							echo form_dropdown( $f['name'], $f['options'], set_value($f['name'], $object[$f['name']]) );
							break;
						case 'textarea':
							echo form_textarea( $f, set_value($f['name'], $object[$f['name']]) );
							break;
						default:
							echo form_input( $f, set_value($f['name'], $object[$f['name']]) );
							break;
					}
					?>
					<?php if( $error ) : ?>
					<span class="help-inline"><?php echo $error; ?></span>
					<?php endif; ?>

					<?php 
					/* if image in misc fields */
					?>
					<?php if( 
							preg_match('/^misc/', $f['name']) &&
							preg_match('/(\.jpg|\.png|\.gif|\.svg)$/i', $object[$f['name']])
							) : 
					?>
						<div>
							<img src="<?php echo $object[$f['name']]; ?>" style="max-width: 100%;">
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="controls">
			<?php echo form_button( array('type' => 'submit', 'name' => 'submit', 'class' => 'btn btn-primary'), lang('common_save')); ?>

			<?php echo ci_anchor( array($this->conf['path'], 'delete', $object['id']), lang('common_delete'), 'class="btn btn-danger hc-confirm"' ); ?>
		</div>
		<?php echo form_close();?>
	</div>

	<div class="span6" style="margin-bottom: 0.5em;">

		<?php if( $geocoded == 1 ) : ?>

			<div class="alert alert-success">
				<?php echo lang('location_coordinates'); ?>: <em><?php echo $object['latitude']; ?>, <?php echo $object['longitude']; ?></em><br>
			</div>
			<div id="lpr-map" style="height: 400px; width: 100%;"></div>

			<script language="JavaScript">
			jQuery(document).ready( function()
			{
				var lpr_map = new google.maps.Map( document.getElementById("lpr-map"), {zoom:15, mapTypeId:google.maps.MapTypeId.ROADMAP} );
				var location_position = new google.maps.LatLng( <?php echo $object['latitude']; ?>, <?php echo $object['longitude']; ?> );
				lpr_map.setCenter( location_position );
				var start_marker = new google.maps.Marker( {
					map: lpr_map,
					position: location_position,
					draggable: false,
					visible:true,
					});
			});	
			</script>

		<?php elseif( $geocoded == 0 ) : ?>

			<div class="alert alert-block" id="lpr-geo-results">
				<?php echo lang('location_not_geocoded'); ?>
			</div>
			<div id="lpr-map" style="height: 400px; width: 100%;"></div>

<script language="JavaScript">
var url_prefix = '<?php echo ci_site_url('admin/locations'); ?>';
var json_url =  [ url_prefix, 'geocode-get', <?php echo $object['id']; ?> ].join('/');

jQuery(document).ready( function()
{
	jQuery.getJSON(
		json_url,
		function( data )
		{
			if( data.id > 0 )
			{
			// get coordinates for location
				var lpr_geocoder = new google.maps.Geocoder();
				lpr_geocoder.geocode(
					{ 'address': data.address },
					function( results, status )
					{
						switch( status )
						{
							case google.maps.GeocoderStatus.OVER_QUERY_LIMIT :
								jQuery("#lpr-geo-results").html( 'Daily limit reached, please try tomorrow' );
								break;

							case google.maps.GeocoderStatus.ZERO_RESULTS:
								var save_url = [ url_prefix, 'geocode-save', data.id, -1, -1 ].join('/');
								jQuery.ajax( save_url )
								.done( function()
									{
										jQuery("#lpr-geo-results").html( 
											[
												data.address,
												'Address not found'
											].join('<br>')
										);
									}
								);
								break;

							case google.maps.GeocoderStatus.OK :
								var loc = results[0].geometry.location;
							// save coordinates
								var save_url = [ url_prefix, 'geocode-save', data.id, loc.lat(), loc.lng() ].join('/');
								jQuery.ajax( save_url )
								.done( function()
								{
									jQuery("#lpr-geo-results").html(
										[
											data.address,
											[loc.lat(), loc.lng()].join(', ')
										].join('<br>')
										);
									jQuery("#lpr-geo-results").attr('class', 'alert alert-success');

									var lpr_map = new google.maps.Map( document.getElementById("lpr-map"), {zoom:15, mapTypeId:google.maps.MapTypeId.ROADMAP} );
									lpr_map.setCenter( loc );

									var start_marker = new google.maps.Marker( {
										map: lpr_map,
										position: loc,
										draggable: false,
										visible:true,
										});
								});
								break;

							default :
								jQuery("#lpr-geo-results").html( 'Geocoding error: ' + status );
								break;
						}
					}
				);
				
			}
			else
			{
				alert( 'Location not found' );
			}
		}
		)

		.fail( function(data)
		{
			alert( 'Error parsing JSON from ' + json_url );
//			alert(JSON.stringify(data));
		}
		);
});
</script>

		<?php elseif( $geocoded == -1 ) : ?>

			<div class="alert alert-error">
				<?php echo lang('location_geocoding_failed'); ?>
			</div>

		<?php endif; ?>
	</div>
</div>
