<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo (isset($page_title)) ? $page_title : ''; ?></title>

<link href="<?php echo ci_base_url('assets/bootstrap/css/_bootstrap.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo ci_base_url('assets/css/lpr.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo ci_base_url('assets/js/jquery-1.8.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo ci_base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<script type="text/javascript" src="//google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
<script type="text/javascript" src="<?php echo ci_base_url('assets/js/lpr.js'); ?>"></script>
</head>
<body>
<?php endif; ?>

<div class="hc">

<?php if( $message ) : ?>
<div class="alert alert-info">
<?php	if( is_array($message) ) : ?>
<ul>
<?php 		foreach( $message as $m ) : ?>
<li><?php 		echo $m; ?></li>
<?php 		endforeach; ?>
</ul>
<?php 	else : ?>
<?php 		echo $message;?>
<?php 	endif; ?>
</div>
<?php endif; ?>

<?php if( isset($include) ) : ?>
<?php echo $this->load->view($include); ?>
<?php endif; ?>

</div><!-- /hc -->

<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
</body>
</html>
<?php endif; ?>