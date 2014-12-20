<?php
if( file_exists(dirname(__FILE__) . '/../../db.php') )
{
	$nts_no_db = TRUE;
	include_once( dirname(__FILE__) . '/../../db.php' );
}
else
{
	error_reporting(0);
	ini_set( 'display_errors', 'Off' );
}

if( ! class_exists('Locatoraid_Base') )
{
if( file_exists(dirname(__FILE__) . '/../../widgets/searchform.php') )
{
	include_once( dirname(__FILE__) . '/../../widgets/searchform.php' );
}

class Locatoraid_Base
{
	var $load_by_js = FALSE;
	var $app = '';
	var $wpi = 0;

	var $slug = '';
	var $hc_product = '';
	var $full_path = '';
	var $system_type = '';
	var $deactivate_other = array();

	var $premium = NULL;

	public function __construct( $wpi = '', $full_path = '' )
	{
		$this->wpi = $wpi;
		$this->app = 'locatoraid' . $this->wpi;
		$this->slug = $this->app;
		$this->full_path = $full_path;
		$this->system_type = 'ci';

		$this->load_by_js = FALSE;
		$this->_init();
		add_action('wp', array($this, 'check_shortcode') );
		add_action('admin_menu', array($this, 'admin_menu') );
		add_shortcode( $this->app, array($this, 'front_view'));
		add_action( 'admin_init', array($this, 'admin_init') );
		$submenu = is_multisite() ? 'network_admin_menu' : 'admin_menu';
		add_action( $submenu, array($this, 'admin_submenu') );
	}

	public function admin_total_init()
	{
		if( $this->premium )
		{
			$this->premium->admin_total_init();
		}
	}

	public function admin_init()
	{
		$this->admin_total_init();
	}

	public function admin_submenu()
	{
		if( $this->premium )
		{
			$this->premium->admin_submenu();
		}
	}

	public function deactivate_other( $plugins = array() )
	{
		$this->deactivate_other = $plugins;
		add_action( 'admin_init', array($this, 'run_deactivate'), 999 );
	}

	public function run_deactivate()
	{
		if( ! $this->deactivate_other )
			return;

		/* check if we have other  activated */
		$deactivate = array();
		$plugins = get_option('active_plugins');
		foreach( $plugins as $pl )
		{
			reset( $this->deactivate_other );
			foreach( $this->deactivate_other as $d )
			{
				if( strpos($pl, $d) !== FALSE )
				{
					$deactivate[] = $pl;
				}
			}
		}

		foreach( $deactivate as $d );
		{
			if( is_plugin_active($d) )
			{
				deactivate_plugins($d);
			}
		}
	}

	function strip_p($content)
	{
		$content = str_replace( '</p>', '', $content );
		$content = str_replace( '<p>', '', $content );
		$content = str_replace( array("&#038;","&amp;"), "&", $content ); 
		return $content;
	}

	function _init()
	{
		$GLOBALS['NTS_CONFIG'][$this->app] = array();
		$GLOBALS['NTS_CONFIG'][$this->app]['ASSETS_DIR'] = plugins_url( 'assets', $this->full_path );

	// database
		global $table_prefix;
		$GLOBALS['NTS_CONFIG'][$this->app]['DB_HOST'] = DB_HOST;
		$GLOBALS['NTS_CONFIG'][$this->app]['DB_USER'] = DB_USER;
		$GLOBALS['NTS_CONFIG'][$this->app]['DB_PASS'] = DB_PASSWORD;
		$GLOBALS['NTS_CONFIG'][$this->app]['DB_NAME'] = DB_NAME;
		$mypref = $table_prefix . 'lctr2_';
		if( $this->wpi )
		{
			$mypref .= $this->wpi . '_';
		}
		$GLOBALS['NTS_CONFIG'][$this->app]['DB_TABLES_PREFIX'] = $mypref;
		$GLOBALS['NTS_IS_PLUGIN'] = 'wordpress';
	}

	function admin_menu( $title = '' )
	{
		if( ! $title )
		{
			$title = 'Locatoraid';
			if( $this->wpi )
			{
				if( substr($this->wpi, 0, 1) == '_' )
					$title .= ' ' . substr($this->wpi, 1);
				else
					$title .= ' ' . $this->wpi;
			}
		}

		$page = add_menu_page( 
			$title, 
			$title,
//			'read',
			'edit_pages',
			$this->app,
			array($this, 'admin_view'),
			'dashicons-location'
			);
		add_action( 'load-' . $page, array($this, 'admin_action') );
		add_action( 'admin_print_styles-' . $page, array($this, 'admin_print_styles') );
		add_action( 'admin_print_scripts-' . $page, array($this, 'admin_print_scripts') );
	}

	function admin_action()
	{
		global $LANG, $CFG, $UNI;

	// action
		$current_user = wp_get_current_user();
		$GLOBALS['NTS_CONFIG'][$this->app]['PREDEFINED_ADMIN'] = $current_user->user_email;
		$GLOBALS['NTS_CONFIG'][$this->app]['FORCE_LOGIN_ID'] = $current_user->ID;
		$GLOBALS['NTS_CONFIG'][$this->app]['FORCE_LOGIN_NAME'] = $current_user->user_email;

		$GLOBALS['NTS_CONFIG'][$this->app]['BASE_URL'] = get_admin_url();
		$GLOBALS['NTS_CONFIG'][$this->app]['INDEX_PAGE'] = 'admin.php?page=' . $this->app . '&';
		$GLOBALS['NTS_CONFIG'][$this->app]['DEFAULT_CONTROLLER'] = 'admin/locations';

		$GLOBALS['NTS_CONFIG']['_app_'] = $this->app;
		$app_title = ucfirst($this->app);
		$app_title = 'Locatoraid';
		if( file_exists(dirname(__FILE__) . '/../modules/pro') )
		{
			$app_title .= ' Pro';
		}
		else
		{
			$GLOBALS['NTS_CONFIG'][$this->app]['nts_app_promo'] = array(
				'http://www.locatoraid.com/order/',
				$app_title . ' Pro'
				);
		}
		$GLOBALS['NTS_CONFIG']['_app_title_'] = $app_title;

		require( dirname(__FILE__) . '/../index_action.php' );
		$GLOBALS['NTS_CONFIG'][$this->app]['ACTION_STARTED'] = 1;
	}

	function admin_view()
	{
	// view
		$ci =& ci_get_instance();
		echo $ci->output->get_output();
	}

	function front_view()
	{
		if( $this->load_by_js )
		{
			$target = ci_site_url('load');
			$html  =<<<EOT
$url
<script type="text/javascript" src="$target"></script>
EOT;
			return $html;
		}
		else
		{
			if( 
				isset($GLOBALS['NTS_CONFIG'][$this->app]['ACTION_STARTED']) && 
				$GLOBALS['NTS_CONFIG'][$this->app]['ACTION_STARTED']
				)
			{
				$ci =& ci_get_instance();
				$output = $ci->output->get_output();
				return $output;
			}
			else
			{
				global $post;
				$link = get_permalink($post);
				$title = get_the_title( $post->ID );
				echo '<a href="' . $link . '">' . $title . '</a>';
			}
		}
	}

	function check_shortcode()
	{
		if( is_admin() )
			return;

		global $post;

		$is_me = FALSE;

/*
		$pattern = get_shortcode_regex();
		if( 
			preg_match_all('/'. $pattern .'/s', $post->post_content, $matches)
			&& array_key_exists(2, $matches)
			&& in_array($this->app, $matches[2])
			)
		{
			$is_me = TRUE;
		}
*/
		if( ! (isset($post) && $post) )
			return $return;

		$pattern = '\[' . $this->app . '\]';
		if(
			preg_match('/'. $pattern .'/s', $post->post_content, $matches)
			)
		{
			$is_me = TRUE;
		}
		else
		{
			// might be shortcode with params
			$pattern = '\[' . $this->app . '\s+(.+)\]';
			if(
				preg_match('/'. $pattern .'/s', $post->post_content, $matches)
				)
			{
				$is_me = TRUE;
				$GLOBALS['NTS_CONFIG'][$this->app]['DEFAULT_PARAMS'] = shortcode_parse_atts( $matches[1] );
			}
		}

		if( $is_me )
		{
			add_filter('the_content', array($this, 'strip_p'), 1000);
			wp_enqueue_script( 'jquery' );

			if( ! $this->load_by_js )
			{
				$this->print_styles();
				wp_enqueue_script( 'lctrScript11', '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true' );
				wp_enqueue_script( 'lctrScript12', '//google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js' );
				$this->print_scripts();
			}

		// action
			$url = parse_url( get_permalink($post) );
			$base_url = $url['path'];
			$index_page = (isset($url['query']) && $url['query']) ? '?' . $url['query'] . '&' : '?/';

			if( substr($base_url, -1) == '/' )
			{
				$base_url = substr($base_url, 0, -1);
			}

			$GLOBALS['NTS_CONFIG'][$this->app]['BASE_URL'] = $base_url;
			$GLOBALS['NTS_CONFIG'][$this->app]['INDEX_PAGE'] = $index_page;
//			echo "bu = $base_url<br>";
//			echo "ip = $index_page<br>";
//			exit;

			$GLOBALS['NTS_CONFIG'][$this->app]['DEFAULT_CONTROLLER'] = 'front';

			$GLOBALS['NTS_CONFIG']['_app_'] = $this->app;
			require( dirname(__FILE__) . '/../index_action.php' );
			$GLOBALS['NTS_CONFIG'][$this->app]['ACTION_STARTED'] = 1;
		}
	}

	public function dev_options()
	{
		if( $this->premium )
		{
			$this->premium->dev_options();
		}
	}

	static function uninstall( $prefix )
	{
		global $wpdb, $table_prefix;

		if( ! strlen($prefix) )
		{
			return;
		}

		$mypref = $table_prefix . $prefix . '_';
		$sql = "SHOW TABLES LIKE '$mypref%'";
		$results = $wpdb->get_results( $sql );
		foreach( $results as $index => $value )
		{
			foreach( $value as $tbl )
			{
				$sql = "DROP TABLE IF EXISTS $tbl";
				$e = $wpdb->query($sql);
			}
		}
	}

	function print_styles()
	{
		$ii = 1;
//		wp_enqueue_style( 'lctrStylesheet' . $ii++, plugins_url('assets/bootstrap/css/_bootstrap.css', $this->full_path) );
		wp_enqueue_style( 'lctrStylesheet' . $ii++, plugins_url('assets/css/lpr.css', $this->full_path) );
		wp_enqueue_style( 'lctrStylesheet' . $ii++, plugins_url('assets/css/hitcode-wp.css', $this->full_path) );
	}
	function print_scripts()
	{
		wp_enqueue_script( 'jquery' );
		$ii = 1;
//		wp_enqueue_script( 'lctrScript' . $ii++, plugins_url('assets/bootstrap/js/bootstrap.min.js', $this->full_path) );
		wp_enqueue_script( 'lctrScript1', plugins_url('assets/js/lpr.js', $this->full_path) );
		wp_enqueue_script( 'lctrScript_front', plugins_url('assets/js/lpr-front.js', $this->full_path) );
	}

	function admin_print_styles()
	{
		$ii = 1;
		wp_enqueue_style( 'lctrStylesheet' . $ii++, plugins_url('assets/bootstrap/css/_bootstrap.css', $this->full_path) );
		wp_enqueue_style( 'lctrStylesheet' . $ii++, plugins_url('assets/css/hitcode-wp.css', $this->full_path) );
		wp_enqueue_style( 'lctrStylesheet' . $ii++, plugins_url('assets/css/lpr.css', $this->full_path) );
	}
	function admin_print_scripts()
	{
		$ii = 1;
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'lctrScript' . $ii++, plugins_url('assets/bootstrap/js/bootstrap.min.js', $this->full_path) );
		wp_enqueue_script( 'lctrScript' . $ii++, plugins_url('assets/js/lpr.js', $this->full_path) );
	}
}
}
?>