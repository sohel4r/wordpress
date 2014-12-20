<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Load extends Front_controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index( $search = '', $search2 = '' )
	{
		$html = '';

		$content_type = 'text/javascript';
		header("Content-type: $content_type");

		$app = $this->config->item('nts_app');
		$assets_dir = isset($GLOBALS['NTS_CONFIG'][$app]['ASSETS_DIR']) ? $GLOBALS['NTS_CONFIG'][$app]['ASSETS_DIR'] : ci_base_url('assets');

		$css = array(
			$assets_dir . '/bootstrap/css/_bootstrap.css',
			$assets_dir . '/css/lpr.css',
			);

		$js = array(
			$assets_dir . '/bootstrap/js/bootstrap.min.js',
			'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true',
			'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js',
//			$assets_dir . '/js/lpr-front.js', // load later after content is ready
			$assets_dir . '/js/lpr.js',
			$assets_dir . '/js/hc-load.js',
			);

		if( ! (isset($GLOBALS['NTS_IS_PLUGIN']) && ($GLOBALS['NTS_IS_PLUGIN'] == 'wordpress')) )
		{
			array_unshift( $js, $assets_dir . '/js/jquery-1.8.3.min.js' );
		}

		if( $search2 )
		{
			$target = ci_site_url( array('front/start', $search, $search2) );
		}
		else
		{
			$target = ci_site_url( array('front/start', $search) );
		}

		$html .= <<<EOT
var hc_target = "$target";

EOT;
		if( ! $search )
		{
			$html .= <<<EOT
var lpr_search = hc_get_param('lpr-search');
var lpr_search2 = hc_get_param('lpr-search2');

if( lpr_search && lpr_search2 )
	hc_target = hc_target + '/' + lpr_search + '/' + lpr_search2;
else if( lpr_search )
	hc_target = hc_target + '/' + lpr_search;

EOT;
		}

		$html .= <<<EOT
function hc_get_param( name )
{
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null )
		return "";
	else
		return results[1];
}

function hc_if_loaded( src, targetelement, targetattr )
{
	var allsuspects = document.getElementsByTagName(targetelement);
	var skip_me = false;
	for( var i = allsuspects.length; i >= 0; i-- )
	{
		if( allsuspects[i] && (allsuspects[i].getAttribute(targetattr) != null) && (allsuspects[i].getAttribute(targetattr).indexOf(src) != -1) )
		{
			skip_me = true;
			break;
		}
	}
	return skip_me;
}

function hc_get_js( src )
{
	if( ! hc_if_loaded(src, 'script', 'src') )
	{
		document.writeln('<' + 'script src="' + src + '"' + ' type="text/javascript"><' + '/script>');
	}
}

function hc_append_js( src )
{
	if( ! hc_if_loaded(src, 'script', 'src') )
	{
		var fileref=document.createElement('script')
		fileref.setAttribute("type","text/javascript")
		fileref.setAttribute("src", src)
		document.getElementsByTagName('head')[0].appendChild( fileref );
	}
}

function hc_get_css( src )
{
	if( ! hc_if_loaded(src, 'link', 'href') )
	{
		var fileref = document.createElement('link');
		fileref.setAttribute( 'rel', 'stylesheet' );
		fileref.setAttribute( 'type', 'text/css' );
		fileref.setAttribute( 'href', src );
		document.getElementsByTagName('head')[0].appendChild( fileref );
	}
}

EOT;

		reset( $css );
		foreach( $css as $f )
		{
			$html .= "hc_get_css('$f');\n";
		}

		foreach( $js as $f )
		{
			$html .= "hc_get_js('$f');\n";
		}

		$html .= <<<EOT

EOT;

	/* also include js init */
		$js_init = $this->load->view('front_js', '', true);
		$html .= $js_init;

		echo $html;
		exit;
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */