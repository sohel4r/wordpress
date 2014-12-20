<?php
/* COMMON */
$lang['common_email'] = 'Email';
$lang['common_password'] = 'Password';
$lang['common_new_password'] = 'New Password';
$lang['common_new_password_confirm'] = 'Confirm Password';
$lang['common_edit'] = 'Edit';
$lang['common_update'] = 'Update';
$lang['common_save'] = 'Save';
$lang['common_search'] = 'Search';
$lang['common_show_all'] = 'Show All';
$lang['common_continue'] = 'Continue';
$lang['common_delete'] = 'Delete';
$lang['common_ok'] = 'OK';
$lang['common_remove'] = 'Remove';
$lang['common_confirm'] = 'Confirm';
$lang['common_select'] = 'Select';
$lang['common_any'] = 'Any';
$lang['common_settings'] = 'Settings';
$lang['common_add'] = 'Add';
$lang['common_none'] = 'None';
$lang['common_no'] = 'No';
$lang['common_yes'] = 'Yes';
$lang['common_yes_required'] = 'Yes [Required]';
$lang['common_yes_required_unique'] = 'Yes [Required, Unique]';
$lang['common_upload'] = 'Upload';
$lang['common_upload_error'] = 'Upload error';
$lang['common_file'] = 'File';
$lang['common_language'] = 'Language';
$lang['common_automatically'] = 'Automatically';
$lang['common_let_user_select'] = 'Let User Select';

$lang['profile_updated'] = 'Profile Updated';

/* SETUP */
$lang['setup_title'] = 'Store Locator Installation';
$lang['setup_email'] = 'Administrator Email';
$lang['setup_password'] = 'Password';
$lang['setup_password2'] = 'Confirm Password';
$lang['setup_setup'] = 'Proceed To Setup';
$lang['setup_ok'] = 'Store locator has been successfuly installed';

/* LOGIN / LOGOUT */
$lang['login'] = 'Login';
$lang['login_successful'] = 'Logged In Successfully';
$lang['login_unsuccessful'] = 'Incorrect Login';
$lang['logout_successful'] = 'Logged Out Successfully';
$lang['logged_in_as'] = 'Logged in as';

/* ADMIN MENU */
$lang['menu_conf'] = 'Configuration';
$lang['menu_conf_form'] = 'Location Form';
$lang['menu_conf_settings'] = 'Settings';
$lang['menu_locations'] = 'Locations';
$lang['menu_locations_view'] = 'View';
$lang['menu_locations_add'] = 'Add';
$lang['menu_locations_import'] = 'Import';
$lang['menu_locations_export'] = 'Export';
$lang['menu_locations_geocode'] = 'Geocode';
$lang['menu_stats'] = 'Stats';
$lang['menu_install'] = 'Install';
$lang['menu_preview'] = 'Preview';
$lang['menu_logout'] = 'Logout';

/* LOCATIONS */
$lang['location_name'] = 'Name';
$lang['location_address'] = 'Address';
$lang['location_street1'] = 'Street Address 1';
$lang['location_street2'] = 'Street Address 1';
$lang['location_city'] = 'City';
$lang['location_state'] = 'State';
$lang['location_zip'] = 'Zip Code';
$lang['location_country'] = 'Country';
$lang['location_phone'] = 'Phone';
$lang['location_website'] = 'Website';
$lang['location_misc'] = 'Misc';
$lang['location_misc_form'] = 'Additional Fields Titles';
$lang['location_misc_form_hide'] = 'Hide In Front View';
$lang['location_total_count'] = 'Total Locations';
$lang['location_matched_count'] = 'Matched Locations';
$lang['location_distance'] = 'Distance';
$lang['location_not_geocoded'] = 'Not Geocoded Yet';
$lang['location_geocoding_failed'] = 'Geocoding Failed';
$lang['location_nothing_found'] = 'Nothing found within %s.';
$lang['location_nothing_found_suggest'] = 'However, there are %s matches within %s.';
$lang['location_coordinates'] = 'Coordinates';
$lang['location_latitude'] = 'Latitude';
$lang['location_longitude'] = 'Longitude';
$lang['location_priority'] = 'Priority';
$lang['location_priority_no'] = 'Normal';
$lang['location_priority_featured'] = 'Featured';
$lang['location_priority_always'] = 'Always Shown';
$lang['location_type'] = 'Type';

$lang['location_list_title'] = 'Locations';
$lang['location_edit'] = 'Edit Location';
$lang['location_add'] = 'Add Location';
$lang['location_import'] = 'Import Locations';
$lang['location_import_help'] = 'We can recognize the following columns in your CSV file';
$lang['location_import_error_fields_missing'] = 'Mandatory fields missing in uploaded file';
$lang['location_import_message_fields_not_recognized'] = 'Some fields in your file are not recognized';
$lang['location_import_ok'] = 'Locations Imported';
$lang['location_import_mode_overwrite'] = 'Delete current locations';
$lang['location_import_mode_append'] = 'Append to current locations';
$lang['location_geocode_title'] = 'Geocode Locations';
$lang['location_products'] = 'Products';
$lang['location_products_field_help'] = 'Separate by comma';
$lang['location_products_file_help'] = 'To import products to locations relations, add your column headers like <strong>product:Pizza</strong> or <strong>product:Wheat Beer</strong>, then put <strong>x</strong> in respective location rows.';

/* CONFIGURATION */
$lang['conf_default_search'] = 'Default Search Text';
$lang['conf_default_search_help'] = 'If you leave this blank, all the locations will be displayed. Please be careful when you have a lot of locations, it is acceptable for up to 50-100 locations in total.';
$lang['conf_append_search'] = 'Append Customer Search With';
$lang['conf_append_search_help'] = 'You may add your country or city name so it will show relevant results even if there might be the same zip code in another place.';
$lang['conf_csv_separator'] = 'CSV Separator';
$lang['conf_measurement'] = 'Measure Units';
$lang['conf_measurement_km'] = 'Km';
$lang['conf_measurement_miles'] = 'Miles';
$lang['conf_search_within'] = 'Search Within';
$lang['conf_search_within_help'] = 'Separate by comma';
$lang['conf_show_sidebar'] = 'Show Locations List';
$lang['conf_group_output'] = 'Group Output';
$lang['conf_group_output_state'] = 'By State';
$lang['conf_group_output_state_city'] = 'By State, Then By City';
$lang['conf_group_output_city'] = 'By City';
$lang['conf_group_output_alphabetical'] = 'Sort Alphabetically';
$lang['conf_limit_output'] = 'Limit Output (number of locations)';
$lang['conf_limit_output_help'] = 'Only numbers please, set to 0 for no limit';
$lang['conf_choose_country'] = 'Choose Country Drop Down';

$lang['conf_trigger_autodetect'] = 'Detect Customer Current Position';
$lang['conf_start_listing'] = 'Start With Default Locations Listing';
$lang['conf_not_found_text'] = 'Not Found Text';
$lang['conf_search_label'] = 'Search Form Label';
$lang['conf_search_button'] = 'Search Form Button Text';
$lang['conf_autodetect_button'] = 'Auto-detect Button Text';
$lang['conf_your_location_label'] = 'Your Location Label';
$lang['conf_show_distance'] = 'Show Distance To Location';

/* TIME */
$lang['time_from'] = 'From';
$lang['time_to'] = 'To';
$lang['time_today'] = 'Today';
$lang['time_yesterday'] = 'Yesterday';
$lang['time_thismonth'] = 'This Month';
$lang['time_lastmonth'] = 'Last Month';
$lang['time_all'] = 'All Time';

/* STATS */
$lang['stats_address'] = 'Search address';
$lang['stats_qty'] = 'Number of searches';

$lang['install_help'] = "Copy the HTML code below and paste it into your web page where you want the front end to appear. Note that this code can <b>ONLY</b> be placed on a web page from the same domain where the script is installed.";
$lang['install_help_wordpress'] = "Make use of the following shortcode in any of your posts or pages";
$lang['install_help_remote_form'] = "To add a locator search form to any other page of your site, please make use of this code.";

/* AUTH */
$lang['auth_login_form_forgot_password'] = 'Forgot your password?';
$lang['auth_password_change_successful'] = 'Password Successfully Changed';
$lang['auth_forgot_password_successful'] 	 	 = 'Password Reset Email Sent';
$lang['auth_forgot_password_unsuccessful'] 	 	 = 'Unable to Reset Password';
$lang['auth_email_new_password_subject']          = 'New Password';

/* FRONT */
$lang['front_address_or_zip'] = 'Address or Zip Code';
$lang['front_search_within'] = 'Within';
$lang['front_product_search'] = 'Product';
$lang['front_autodetect'] = 'Auto-detect your location';
$lang['front_current_location'] = 'Your location';
$lang['front_enter_address'] = 'New search?';
$lang['front_directions'] = 'Directions';

