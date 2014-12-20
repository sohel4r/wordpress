<?php
$search_value = isset($_GET['lpr-search']) ? $_GET['lpr-search'] : '';
?>
<form action="<?php echo $locator_page; ?>" method="get">
<?php echo $label; ?> 
<input type="text" name="lpr-search" value="<?php echo $search_value; ?>" id="lpr-search"  />
<input type="submit" value="<?php echo $btn; ?> ">
</form>
<br>