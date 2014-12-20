<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
<?php	require( dirname(__FILE__) . '/_layout/head.php' ); ?>
<?php endif; ?>

<div class="hc">
<div class="container-fluid">
<?php	require( dirname(__FILE__) . '/_layout/menu.php' ); ?>
<?php	require( dirname(__FILE__) . '/_layout/main.php' ); ?>
</div><!-- /container -->
</div><!-- /hc -->

<?php if( ! isset($GLOBALS['NTS_IS_PLUGIN']) ) : ?>
</body>
</html>
<?php endif; ?>