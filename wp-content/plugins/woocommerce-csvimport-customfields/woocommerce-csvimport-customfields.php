<?php
/*
Plugin Name: Woocommerce CSV Import Custom field add-on
#Plugin URI: http://allaerd.org/
Description: Import custom fields
Version: 1.0.0
Author: Allaerd Mensonides
License: GPLv2 or later
Author URI: http://allaerd.org
*/

class woocsvCustomFields {

	public function __construct() {

		add_action('woocsv_after_save',array($this,'saveCustomFields'));
		add_action('wp_ajax_saveCustomFields', array($this, 'saveCustomFieldForm'));
		
		add_action('admin_init', array($this, 'initJsCss'));
		add_action('admin_menu', array($this,'adminMenu'));
		
		$this->addToFields();
	}
		
	public function adminMenu() {
		add_submenu_page( 'woocsv_import', 'Custom Fields', 'Custom Fields', 'manage_options', 'woocsvCustomfields', array($this,'addToAdmin'));
	}
	
	public function addToFields() {
		global $woocsvImport;
		$customFields = get_option('woocsv-customfields');
		if ($customFields) {
			$customFields = explode(',', $customFields);
			foreach ($customFields as $key=>$value) {
				$woocsvImport->fields[] = 'cf_'.$value;
				}
		}
		
	}

	public function initJsCss () {
		wp_register_script( 'woocsv-custom-field-script', plugins_url( '/woocommerce-csvimport-customfields/woocsv-custom-field.js' ) );
		wp_enqueue_script( 'woocsv-custom-field-script' );
	}

	public function saveCustomFieldForm() {
		$customfields = $_POST['customFields'];
		update_option('woocsv-customfields', trim($customfields));
		wp_die('<p>Custom fields saved!</p>');
	}

	public function saveCustomFields ($product) {
		foreach ($product->header as $key=>$value) {
			if (substr($value,0,3) === 'cf_') {				
				if (isset($product->rawData[$key])) {
					update_post_meta( $product->body['ID'], substr($value,3), $product->rawData[$key]);
				}
			}
		}
	}
	
	function addToAdmin () {
	?>
		<div class="wrap">
		<div id="woocsv_warning" style="display:none" class="updated"></div>
		<h2>Add your custom fields</h2>
		<p>You can fill in your custom fields here. Fill them in as a comma separated list.
		Example: customfield1,customfield2,customfield3</p>
		<p>You can select them when you create your header as cf_customfield1,cf_customfield2, etc.....</p>
		<form id="customFieldForm" method="POST">
		<table class="form-table">
		<tbody>
			<tr>
				<th scope="row" class="titledesc"><label for="seperator">Custom fields</label></th>
				<td>
					<input type="text" size="100" placeholder="list your customfields comma seperated" 
						name="customFields" value="<?php echo get_option('woocsv-customfields');?>">
				</td>
			</tr>
			<tr>
				<td><button type="submit" class="button-primary">Save</button></td>
			</tr>
		</tbody>
		</table>

		<input type="hidden" name="action" value="saveCustomFields">
		</form>
		</div>
		<?php
	}
}

		/*
		//text attributes
		unset($product_attributes);
		foreach ($header as $key=>$value) {
			if (substr($value,0,3) === 'pa_') {
			
			$product_attributes['pa_'.substr($value,3)] = array
				(
					'name' => 'pa_'.substr($value,3),
					'value' => ($data[$key]),
					'position' => '0',
					'is_visible' => '1',
					'is_variation' => '0',
					'is_taxonomy' => '1',
				);	
			}
		wp_set_object_terms( $post_id,($data[$key]), 'pa_'.substr($value,3), true );
		}
		update_post_meta( $post_id, '_product_attributes' , $product_attributes );
		//clear some stuff
		delete_option("product_cat_children");
		*/