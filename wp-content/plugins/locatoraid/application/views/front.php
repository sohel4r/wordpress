<?php require( dirname(__FILE__) . '/front_init.php' ); ?>

<?php require( $layout_file ); ?>

<?php if( ! (isset($GLOBALS['NTS_IS_PLUGIN']) && ($GLOBALS['NTS_IS_PLUGIN'] == 'wordpress')) ) : ?>
<script language="JavaScript">
<?php require( dirname(__FILE__) . '/front_js.php' ); ?>
</script>
<?php endif; ?>