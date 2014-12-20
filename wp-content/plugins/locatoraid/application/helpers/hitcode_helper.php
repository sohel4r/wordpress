<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('_print_r'))
{
	function _print_r( $thing )
	{
		echo '<pre>';
		print_r( $thing );
		echo '</pre>';
	}
}

if ( ! function_exists('hc_format_price'))
{
	function hc_format_price( $amount, $calculated_price = '' ){
		$CI =& ci_get_instance();

		$before_sign = $CI->app_conf->get( 'currency_sign_before' );
		$currency_format = $CI->app_conf->get( 'currency_format' );
		list( $dec_point, $thousand_sep ) = explode( '||', $currency_format );
		$after_sign = $CI->app_conf->get( 'currency_sign_after' );

		$amount = number_format( $amount, 2, $dec_point, $thousand_sep );
		$return = $before_sign . $amount . $after_sign;

		if( strlen($calculated_price) && ($amount != $calculated_price) ){
			$calc_format = $before_sign . number_format( $calculated_price, 2, $dec_point, $thousand_sep ) . $after_sign;
			$return = $return . ' <span style="text-decoration: line-through;">' . $calc_format . '</span>';
			}
		return $return;
		}
}

if ( ! function_exists('hc_list_subfolders'))
{
	function hc_list_subfolders( $dirName ){
		if( ! is_array($dirName) )
			$dirName = array( $dirName );

		$return = array();
		reset( $dirName );
		foreach( $dirName as $thisDirName ){
			if ( file_exists($thisDirName) && ($handle = opendir($thisDirName)) ){
				while ( false !== ($f = readdir($handle)) ){
					if( substr($f, 0, 1) == '.' )
						continue;
					if( is_dir( $thisDirName . '/' . $f ) ){
						if( ! in_array($f, $return) )
							$return[] = $f;
						}
					}
				closedir($handle);
				}
			}

		sort( $return );
		return $return;
		}
}

if ( ! function_exists('hc_urlify'))
{
	function hc_urlify($str)
	{
		$return = str_replace( '_', '-', $str );
		return $return;
	}
}

if ( ! function_exists('hc_build_csv'))
{
	function hc_build_csv( $array, $separator = ',' )
	{
		$processed = array();
		reset( $array );
		foreach( $array as $a ){
			if( strpos($a, '"') !== false ){
				$a = str_replace( '"', '""', $a );
				}
			if( strpos($a, $separator) !== false ){
				$a = '"' . $a . '"';
				}
			$processed[] = $a;
			}

		$return = join( $separator, $processed );
		return $return;
	}
}

if ( ! function_exists('hc_expandable_list'))
{
	function hc_expandable_list( $entries, $title_tag = 'name', $children_tag = 'items', $url_function = NULL )
	{
		$return = '';
		$return .= '<ul class="hc-expandable-list">';
		reset( $entries );
		foreach( $entries as $e ){
			$return .= '<li>';
			$return .= '<span>';
			$return .= $e[ $title_tag ];
			$return .= '</span>';
			if( $e[$children_tag] ){
				$return .= '<ul>';
				reset($e[$children_tag]);
				foreach( $e[$children_tag] as $c ){
					$return .= '<li>';
					if( $url_function ){
						$target = $url_function($c);
						$return .= '<a href="' . $target . '">';
						}
					$return .= '<b>' . $c[ $title_tag ] . '</b>';
					
					
					if( isset($c['description']) )
						$return .= '<p>' . $c['description'] . '</p>';
					if( $url_function ){
						$return .= '</a>';
						}
					$return .= '</li>';
					}
				$return .= '</ul>';
				}
			$return .= '</li>';
			}
		$return .= '</ul>';
		
		$return .= "\n";
		$return .= "<script language=\"JavaScript\">\n";
		$return .= "hc_expandable_list();\n";
		$return .= "</script>\n";
		$return .= "\n";
		return $return;
	}
}

/* End of file array_helper.php */
/* Location: ./application/helpers/hitcode.php */