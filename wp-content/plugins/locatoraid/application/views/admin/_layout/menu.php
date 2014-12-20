<?php
$whitelabel_file = FCPATH . '/whitelabel.php';
if( file_exists($whitelabel_file) )
{
	require($whitelabel_file);
}
$brand_title = isset($whitelabel['title']) ? $whitelabel['title'] : $this->config->item('nts_app_title');
$brand_url = isset($whitelabel['url']) ? $whitelabel['url'] : 'http://www.' . $this->config->item('nts_app') . '.com';
$promo = $this->config->item('nts_app_promo');

$this->load->model('Location_model');
$lm = new Location_Model;
$lm->condition_not_yet();
$not_yet_count = $lm->count_all();

$lm->condition_failed();
$failed_count = $lm->count_all();

$warning_view = '';
if( $not_yet_count > 0 )
{
	$warning_view .= '<span class="badge badge-warning">' . $not_yet_count . '</span>';
}
if( $failed_count > 0 )
{
	$warning_view .= '<span class="badge badge-important">' . $failed_count . '</span>';
}
?>
<?php if ( $this->auth->logged_in() && $this->auth->is_admin() ) : ?>
	<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
		<p>
			<h3>
			<a class="brand" target="_blank" href="<?php echo $brand_url; ?>"><?php echo $brand_title; ?></a>
			<small><?php echo HC_APP_VERSION; ?></small>
			</h3>
		</p>
	<?php else : ?>
		<br>
	<?php endif; ?>

<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
	<?php require( dirname(__FILE__) . '/profile.php' ); ?>
<?php endif; ?>

<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
			</a>

			<div class="nav-collapse">
				<ul class="nav">

				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<?php echo lang('menu_locations'); ?>  <?php echo $warning_view; ?> <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">  
						<li>
							<a href="<?php echo ci_site_url('admin/locations'); ?>"><?php echo lang('menu_locations_view');?></a>
						</li>
						<li>
							<a href="<?php echo ci_site_url( array('admin', 'locations', 'add') ); ?>"><?php echo lang('menu_locations_add');?></a>
						</li>
					<?php if( Modules::exists('pro') ) : ?>
						<li>
							<a href="<?php echo ci_site_url('pro/admin/locations/import'); ?>"><?php echo lang('menu_locations_import');?></a>
						</li>
						<li>
							<a href="<?php echo ci_site_url('pro/admin/locations/export'); ?>"><?php echo lang('menu_locations_export');?></a>
						</li>
					<?php endif; ?>
						<?php if( $not_yet_count ) : ?>
							<li>
								<a href="<?php echo ci_site_url('admin/locations/geocode'); ?>">
									<?php echo lang('menu_locations_geocode');?> <?php echo $warning_view; ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</li>

				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo lang('menu_conf'); ?><b class="caret"></b></a>
					<ul class="dropdown-menu">  
						<li>
							<a href="<?php echo ci_site_url( array('admin', 'conf', 'settings') ); ?>"><?php echo lang('menu_conf_settings');?></a>
						</li>
					<?php if( Modules::exists('pro') ) : ?>
						<li>
							<a href="<?php echo ci_site_url('pro/admin/conf/form'); ?>"><?php echo lang('menu_conf_form');?></a>
						</li>
					<?php endif; ?>
					</ul>
				</li>

				<?php if( Modules::exists('pro') ) : ?>
					<li>
						<a href="<?php echo ci_site_url('pro/admin/stats'); ?>"><?php echo lang('menu_stats');?></a>
					</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo ci_site_url('admin/install'); ?>"><?php echo lang('menu_install');?></a>
				</li>

				<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
					<li>
						<a href="<?php echo ci_site_url('admin/install/preview'); ?>"><?php echo lang('menu_preview');?></a>
					</li>
				<?php endif; ?>

				<?php if( $promo ) : ?>
					<li>
						<a target="_blank" href="<?php echo $promo[0]; ?>"><span class="alert alert-success"><?php echo $promo[1]; ?> <i class="icon-arrow-right"></i></span></a>
					</li>
				<?php endif; ?>
				</ul>
			</div><!-- /.nav-collapse -->

		</div><!-- /.container -->
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<?php endif; ?>