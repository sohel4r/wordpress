<?php
$products_label = $this->app_conf->get( 'form_products' );
if( ! strlen($products_label) )
{
	$products_label = lang('front_product_search');
}

if( count($within_options) > 1 )
{
	$measure = $this->app_conf->get( 'measurement' );
	$measure_title = lang('conf_measurement_') . $measure;
	$dropdown_within = array();
	foreach( $within_options as $wo )
	{
		$dropdown_within[ $wo ] = $wo . ' ' . $measure_title;
	}
}
if( $product_options )
{
	$do_options = array(
		' '	=> ' - ' . $products_label . ' - ',
		);
	foreach( $product_options as $po )
	{
		$do_options[ $po ] = $po;
	}
}

$conf_trigger_autodetect = $this->app_conf->get('trigger_autodetect');