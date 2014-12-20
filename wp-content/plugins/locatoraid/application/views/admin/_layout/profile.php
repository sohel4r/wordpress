<?php if ( $this->auth->logged_in() && $this->auth->is_admin() ) : ?>
<?php
		$userdata = $this->auth->get_userdata();
?>
<div>
	<small>
	<?php echo lang('logged_in_as'); ?>: <a href="<?php echo ci_site_url('auth/profile'); ?>"><?php echo $userdata['identity']; ?></a>
	<a href="<?php echo ci_site_url('auth/logout'); ?>"><?php echo lang('menu_logout');?></a>
	</small>
</div><!-- /profile -->
<?php endif; ?>