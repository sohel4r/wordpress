<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript">
var lpr_geocoder = new google.maps.Geocoder();
</script>

<div class="page-header">
<h2><?php echo lang('location_geocode_title');?></h2>
</div>

<div id="lpr-locations-result-status"></div>
<div id="lpr-locations-result"></div>

<script language="JavaScript">
lpr_next_location( "<?php echo ci_site_url($this->conf['path']); ?>", 0, null );
</script>