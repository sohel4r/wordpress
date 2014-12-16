<?php
define('Redux_TEXT_DOMAIN', 'redux-opts');

if(!class_exists('Redux_Options')){
    require_once(dirname(__FILE__) . '/options/options.php');
}

/*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $args are required, but they can be over ridden if needed.
 *
 */
function setup_store_locator_wpress(){
    $args = array();

    // Setting dev mode to true allows you to view the class settings/info in the panel.
    // Default: false
    $args['dev_mode'] = false;

    // If you want to use Google Webfonts, you MUST define the api key.
    //$args['google_api_key'] = 'xxxx';

    // Define the starting tab for the option panel.
    // Default: '0';
    //$args['last_tab'] = '0';

    // Define the option panel stylesheet. Options are 'standard', 'custom', and 'none'
    // If only minor tweaks are needed, set to 'custom' and override the necessary styles through the included custom.css stylesheet.
    // If replacing the stylesheet, set to 'none' and don't forget to enqueue another stylesheet!
    // Default: 'standard'
    //$args['admin_stylesheet'] = 'standard';

    // Add HTML before the form.
    //$args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', Redux_TEXT_DOMAIN);

    // Add content after the form.
    $args['footer_text'] = __('<p>This plugin is protected by International copyright laws. Please make sure you have a valid license before using this plugin on your website. Refer to our <a href="http://yougapi.com/license/">license page</a> for more information.</p>', Redux_TEXT_DOMAIN);
    
    // Set footer/credit line.
    $args['footer_credit'] = __('<p>Powered by <a href="http://yougapi.com" target="_blank">Yougapi Technology</a> - <a href="http://codecanyon.net/user/yougapi/portfolio?ref=yougapi">Apps & plugins portfolio</a></p>', Redux_TEXT_DOMAIN);
    
    // Setup custom links in the footer for share icons
    $args['share_icons']['twitter'] = array(
        'link' => 'http://twitter.com/yougapi',
        'title' => 'Follow me on Twitter', 
        'img' => Redux_OPTIONS_URL . 'img/icons/glyphicons_322_twitter.png'
    );
    $args['share_icons']['facebook'] = array(
        'link' => 'http://www.facebook.com/yougapi',
        'title' => 'Find me on Facebook', 
        'img' => Redux_OPTIONS_URL . 'img/icons/glyphicons_320_facebook.png'
    );

    // Enable the import/export feature.
    // Default: true
    $args['show_import_export'] = false;

    // Set a custom option name. Don't forget to replace spaces with underscores!
    $args['opt_name'] = 'ygp_store_locator_wpress';

    // Set a custom menu icon.
    //$args['menu_icon'] = '';

    // Set a custom title for the options page.
    // Default: Options
    $args['menu_title'] = __('Store Locator WPress', Redux_TEXT_DOMAIN);
    
    // Set a custom page title for the options page.
    // Default: Options
    $args['page_title'] = __('Store Locator WPress', Redux_TEXT_DOMAIN);

    // Set a custom page slug for options page (wp-admin/themes.php?page=***).
    $args['page_slug'] = 'store_locator_wpress';

    // Set a custom page capability.
    // Default: manage_options
    //$args['page_cap'] = 'manage_options';

    // Set the menu type. Set to "menu" for a top level menu, or "submenu" to add below an existing item.
    // Default: menu
    $args['page_type'] = 'submenu';

    // Set the parent menu.
    // Default: themes.php
    // A list of available parent menus is available at http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    $args['page_parent'] = 'plugins.php';

    // Set a custom page location. This allows you to place your menu where you want in the menu order.
    // Must be unique or it will override other items!
    // Default: null
    //$args['page_position'] = null;

    // Set a custom page icon class (used to override the page icon next to heading)
    //$args['page_icon'] = 'icon-themes';

    // Disable the panel sections showing as submenu items.
    // Default: true
    //$args['allow_sub_menu'] = false;
    
    $sections = array();
    
    /*
    $sections[] = array(
        'title' => __('Getting Started', Redux_TEXT_DOMAIN),
        'desc' => __('<p class="description">This is the description field for this section. HTML is allowed</p>', Redux_TEXT_DOMAIN),
        'icon' => Redux_OPTIONS_URL . 'img/icons/glyphicons_062_attach.png'
    );
    */
    
	$map_types_tab = array('roadmap'=>'Roadmap', 'satellite'=>'Satellite', 'hybrid'=>'Hybrid', 'terrain'=>'Terrain');
	$distance_tab = array('km'=>'Kilometers', 'miles'=>'Miles');
	$closest_stores_tab = array('1'=>'Yes', '2'=>'No');
	$yes_no_tab = array('1'=>'Yes', '2'=>'No');
	
    $sections[] = array(
        //'icon' => Redux_OPTIONS_URL . 'img/icons/glyphicons_107_text_resize.png',
        'title' => __('Store locator settings', Redux_TEXT_DOMAIN),
        'desc' => __('<p class="description">Main store locator settings</p>', Redux_TEXT_DOMAIN),
        'fields' => array(
            array(
                'id' => 'nb_display_search',
                'type' => 'text',
                'title' => __('Number of search results', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Number of stores to display when a search is made (per page or per Map) - 10 by default', Redux_TEXT_DOMAIN),
                'std' => '10'
            ),
            array(
                'id' => 'nb_display_default',
                'type' => 'text',
                'title' => __('Number of results by default', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Number of stores to display when the locator loads the first time - 50 by default', Redux_TEXT_DOMAIN),
                'std' => '50'
            ),
            array(
                'id' => 'closest_stores',
                'type' => 'radio',
                'title' => __('Closest stores', Redux_TEXT_DOMAIN), 
                'sub_desc' => __('Detect the user\'s location and load the closest stores when the locator loads the first time', Redux_TEXT_DOMAIN),
                'options' => $closest_stores_tab, // Must provide key => value pairs for radio options
                'std' => '1'
            ),
            array(
                'id' => 'distance_unit',
                'type' => 'radio',
                'title' => __('Distance unit', Redux_TEXT_DOMAIN), 
                'sub_desc' => __('Used for the search results', Redux_TEXT_DOMAIN),
                'options' => $distance_tab,
                'std' => 'km'
            ),
            array(
                'id' => 'custom_marker',
                'type' => 'text',
                'title' => __('Custom marker', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Full URL of the custom marker to use. Need to start with http://', Redux_TEXT_DOMAIN),
                'std' => ''
            ),
            array(
                'id' => 'locator_url',
                'type' => 'text',
                'title' => __('Locator URL', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Full URL of the page where the shortcode is used. Need to start with http://', Redux_TEXT_DOMAIN),
                'std' => ''
            ),
            array(
                'id' => 'direction_links',
                'type' => 'radio',
                'title' => __('Direction links', Redux_TEXT_DOMAIN), 
                'sub_desc' => __('Display direction links in the marker infowindow', Redux_TEXT_DOMAIN),
                'options' => $yes_no_tab,
                'std' => '1'
            ),
            array(
                'id' => 'streetview',
                'type' => 'radio',
                'title' => __('Streetview', Redux_TEXT_DOMAIN), 
                'sub_desc' => __('Display streetview link in the marker infowindow', Redux_TEXT_DOMAIN),
                'options' => $yes_no_tab,
                'std' => '1'
            ),
        )
    );
    
    $sections[] = array(
        'title' => __('General Map settings', Redux_TEXT_DOMAIN),
        'desc' => __('<p class="description">Main Map settings</p>', Redux_TEXT_DOMAIN),
        'fields' => array(
            array(
                'id' => 'map_width',
                'type' => 'text',
                'title' => __('Map width', Redux_TEXT_DOMAIN),
                'sub_desc' => __('100% or any value in pixels - Ex: 480px', Redux_TEXT_DOMAIN),
                'std' => '100%'
            ),
            array(
                'id' => 'map_height',
                'type' => 'text',
                'title' => __('Map height', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Any value in pixels - Ex: 380px', Redux_TEXT_DOMAIN),
                'std' => '380px'
            ),
            array(
                'id' => 'map_type',
                'type' => 'radio',
                'title' => __('Map type', Redux_TEXT_DOMAIN), 
                'sub_desc' => __('', Redux_TEXT_DOMAIN),
                'options' => $map_types_tab, // Must provide key => value pairs for radio options
                'std' => 'roadmap'
            ),
            array(
                'id' => 'map_lat',
                'type' => 'text',
                'title' => __('Default map latitude', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Used to display a default location when no results are loaded by default', Redux_TEXT_DOMAIN),
                'std' => ''
            ),
            array(
                'id' => 'map_lng',
                'type' => 'text',
                'title' => __('Default map longitude', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Used to display a default location when no results are loaded by default', Redux_TEXT_DOMAIN),
                'std' => ''
            ),
            array(
                'id' => 'map_zoom',
                'type' => 'text',
                'title' => __('Default map zoom', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Possible values: from 0 to 20 - Used when the default location is displayed', Redux_TEXT_DOMAIN),
                'std' => '13'
            ),
        )
    );
    
    $sections[] = array(
        'title' => __('Store details settings', Redux_TEXT_DOMAIN),
        'desc' => __('<p class="description">Settings related to the Map displayed in the store detail individual pages</p>', Redux_TEXT_DOMAIN),
        'fields' => array(
            array(
                'id' => 'map_width_detail',
                'type' => 'text',
                'title' => __('Map width', Redux_TEXT_DOMAIN),
                'sub_desc' => __('100% or any value in pixels - Ex: 480px', Redux_TEXT_DOMAIN),
                'std' => '100%'
            ),
            array(
                'id' => 'map_height_detail',
                'type' => 'text',
                'title' => __('Map height', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Any value in pixels - Ex: 380px', Redux_TEXT_DOMAIN),
                'std' => '380px'
            ),
            array(
                'id' => 'map_type_detail',
                'type' => 'radio',
                'title' => __('Map type', Redux_TEXT_DOMAIN), 
                'sub_desc' => __('', Redux_TEXT_DOMAIN),
                'options' => $map_types_tab, // Must provide key => value pairs for radio options
                'std' => 'roadmap'
            ),
            array(
                'id' => 'map_zoom_detail',
                'type' => 'text',
                'title' => __('Default map zoom', Redux_TEXT_DOMAIN),
                'sub_desc' => __('Possible values: from 0 to 20', Redux_TEXT_DOMAIN),
                'std' => '15'
            ),
        )
    );
    
 	$a1=new Store_locator_wpress_activation();$sections2[]=array('title'=>__('Plugin Activation',Redux_TEXT_DOMAIN),'desc'=>__($a1->plugin_activation(),Redux_TEXT_DOMAIN),);$d=$a1->get_domain();$o=get_option($GLOBALS['ygp_store_locator_wpress']['item_name'].'-'.$d);if($o!=md5($GLOBALS['ygp_store_locator_wpress']['item_name'])){$sections=$sections2;}if(!$a1->verify_activation()){$sections=$sections2;}
	
    global $Redux_Options;
    $Redux_Options = new Redux_Options($sections, $args);

}
if(!is_multisite()) add_action('init', 'setup_store_locator_wpress', 0);

