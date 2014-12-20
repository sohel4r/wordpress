<div class="page-header">
<h2><?php echo $page_title; ?> OK</h2>
</div>

<?php
$brand_title = $this->config->item('nts_app_title');
?>
<p>
Thank you for installing <strong><?php echo $brand_title; ?></strong>! Please now proceed to the <a href="<?php echo ci_site_url(); ?>">start page</a>.
</p>

<META http-equiv="refresh" content="5;URL=<?php echo ci_site_url(); ?>">

<?php
$localhost = ($this->input->server('SERVER_NAME') != 'localhost') ? FALSE : TRUE;
$track_setup = '';
$app = isset($GLOBALS['NTS_CONFIG']['_app_']) ? $GLOBALS['NTS_CONFIG']['_app_'] : 'lookuprunner';
switch( $app )
{
	case 'locatoraid':
		$track_setup = '17:2';
		break;
	case 'lookuprunner':
		$track_setup = '4:2';
		break;
}
if( $track_setup )
{
	list( $track_site_id, $track_goal_id ) = explode( ':', $track_setup );
}
?>
<?php if( $track_setup ) : ?>
<?php if( $localhost ) : ?>
	<?php // echo 'TRACKING ' . $track_site_id . ':' . $track_goal_id; ?>
<?php else : ?>
<br><br><br><br>

<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://www.fiammante.com/piwik/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "<?php echo $track_site_id; ?>"]);
	_paq.push(['trackGoal', <?php echo $track_goal_id; ?>]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<?php endif; ?>
<?php endif; ?>