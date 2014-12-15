<?php
 class Store_locator_wpress_activation{
 	function Store_locator_wpress_activation(){
 		add_action('wp_ajax_nopriv_activation_wpress_listener',
 			array(__CLASS__,'ajax_listener'));
 		add_action('wp_ajax_activation_wpress_listener',
 			array(__CLASS__,'ajax_listener'));}
 		function verify_activation(){$d=$this->get_domain();
 			$o=get_option($GLOBALS['ygp_store_locator_wpress']['item_name']
 				.'-'.$d);if($o!=''){if($o==md5($GLOBALS['ygp_store_locator_wpress']
 					['item_name']))$code=1;}return $code;}
 				function get_domain(){$s=site_url();$p=parse_url($s);
 					$d=$p['host'];return $d;}
 					function plugin_activation(){$d.='<script>
		jQuery("#activate_plugin_btn").live(\'click\', function(event) {
			event.preventDefault();
			var purchase_code = jQuery("#purchase_code").val();
			jQuery.ajax({
				type: \'POST\',
				url: \''.admin_url('admin-ajax.php').'\',
				data: \'action=activation_wpress_listener&method=
				plugin_activation&purchase_code=\'+purchase_code,
				success: function(msg) {
					if(msg!="") alert(msg);
					window.location.reload();
				}
			});
		});
		</script>';$d.='<p><b>Purchase code:</b></p>';
		$d.='<input type="text" id="purchase_code" name="purchase_code" 
		style="width:50%;"> ';$d.='<input type="submit" id="activate_plugin_btn"
		 value="Activate my plugin">';
		 $d.='<p><a href="http://yougapi.com/2014/07/find-my-envato-item-purchase-code/"
		 target="_blank">Where can I find my purchase code?</a></p>';
		 $d.='<p>You have a question or need help? 
		 <a href="mailto:contact@yougapi.com">contact@yougapi.com</a></p>';return $d;}
		 function ajax_listener(){$method=$_POST['method'];
		 $purchase_code=$_POST['purchase_code'];
		 $verif_url='http://yougapi.com/updates/verify_purchase.php';
		 if($method=='plugin_activation'){$url=$verif_url.'?
		 	item='.$GLOBALS['ygp_store_locator_wpress']['item_name'].
		 '&url='.site_url().'&purchase_code='.$purchase_code;$data=wp_remote_get($url);
		 $data=json_decode($data['body'],true);if($data['code']==2){echo $data['message'];}
		 else if($data['code']==1){$parse=parse_url(site_url());if(md5($parse['host'])==
		 	$data['domain']){update_option($GLOBALS['ygp_store_locator_wpress']['item_name'].
		 	'-'.$parse['host'],md5($GLOBALS['ygp_store_locator_wpress']['item_name']));
		 	echo $data['message'];}else{echo 'Error (domain error) activating your plugin.
		 	 Please contact our support team for assistance';}}else
		 	 {echo 'Error (connection error) activating your plugin. Please contact our 
		 	 support team for assistance';}exit;}}}new Store_locator_wpress_activation();?>