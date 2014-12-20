<div class="page-header">
<h2><?php echo lang('location_list_title');?></h2>
</div>

<div class="row-fluid">

<div class="span4">
<?php echo form_open( hc_urlify($this->conf['path']) . '/search', array('class' => 'form-horizontal form-condensed') ); ?>
	<ul class="list-inline">
		<li>
			<?php echo form_input( array('name' => 'search', 'class' => 'input-medium'), set_value('search', $show_search)); ?>
		</li>
		<li>
			<?php echo form_button( array('name' => 'submit', 'type' => 'submit', 'class' => 'btn'), lang('common_search'));?>
		</li>
		<?php if( $show_search ) : ?>
			<li>
				<?php echo ci_anchor( hc_urlify($this->conf['path']), lang('common_show_all'), 'class="btn"' ); ?>
			</li>
		<?php endif; ?>
	</ul>
<?php echo form_close();?>
</div>

<div class="span3">
<p>
<?php
	$text = lang('location_total_count') . ': ' . $total_count;
?>
<?php echo ci_anchor( array($this->conf['path']), $text ); ?>


<?php if( $failed_count ) : ?>
	<br>
<?php
	$text = lang('location_geocoding_failed') . ': ' . $failed_count;
?>
	<?php if( $search == '_failed_' ) : ?>
		<strong><?php echo $text; ?></strong>
	<?php else : ?>
		<?php echo ci_anchor( array($this->conf['path'], 'search', '-failed-'), $text ); ?>
	<?php endif; ?>
<?php endif; ?>

<?php if( $not_yet_count ) : ?>
	<br>

<?php
	$text = lang('location_not_geocoded') . ': ' . $not_yet_count;
?>
	<?php if( $search == '_notyet_' ) : ?>
		<strong><?php echo $text; ?></strong>
	<?php else : ?>
		<?php echo ci_anchor( array($this->conf['path'], 'search', '-notyet-'), $text ); ?>
	<?php endif; ?>
<?php endif; ?>

<?php if( $show_search && $matched_count ) : ?>
	<br><?php echo lang('location_matched_count'); ?>: <?php echo $matched_count; ?>
<?php endif; ?>
</p>
</div>

<div class="span5">
<?php echo $this->pagination->create_links(); ?>
</div>

</div>


<?php if( count($entries) ) : ?>

<?php
$per_row = 3;
$row_open = FALSE;
?>

<?php for( $ii = 1; $ii <= count($entries); $ii++ ) : ?>

<?php if( 1 == ($ii % $per_row) ) : ?>
	<div class="row-fluid">
	<?php $row_open = TRUE; ?>
<?php endif; ?>

<?php	$e = $entries[$ii - 1]; ?>
<?php
// check if it is geocoded
if( (! $e['latitude']) && (! $e['longitude']) ){
	$class = 'alert alert-block';
	$status = lang('location_not_geocoded');
	}
elseif( ($e['latitude'] == -1) && ($e['longitude'] == -1) ){
	$class = 'alert alert-error';
	$status = lang('location_geocoding_failed');
	}
else {
	$class = 'alert alert-success';
	$status = join( ', ', array($e['latitude'],$e['longitude']) );
	}

if( Modules::exists('locazip') )
{
	$link_title = $e['zip'];
}
else
{
	$link_title = $e['name'];
}
?>
	<div class="span4">
		<div class="<?php echo $class; ?>">
		<?php echo ci_anchor( 'admin/locations/delete/' . $e['id'], '&times;', 'class="close hc-confirm" title="' . lang('common_delete') . '"' ); ?>

<?php	if( ($e['loc_type'] > 0) && $this->model->get_types() ) : ?>
<h4><?php echo $this->model->type_title($e['loc_type']); ?></h4>
<?php	endif; ?>

		<?php echo ci_anchor( array($this->conf['path'], 'edit', $e['id']), '<strong>' . $link_title . '</strong>' ); ?>
		<br>
		<?php echo $e['view']; ?>
		<br><br><em><?php echo $status; ?></em>
		
		<?php if( Modules::exists('locazip') ) : ?>
			<?php
			$companies = $this->model->get_companies_by( 'zip', $e['zip'] );
			?>
			<p>
				Companies: <?php echo count($companies); ?>
			</p>
		<?php endif; ?>
		</div>
	</div>

<?php if( ! ($ii % $per_row) ) : ?>
	</div>
	<?php $row_open = FALSE; ?>
<?php endif; ?>
<?php endfor; ?>

<?php if( $row_open ) : ?>
	</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span5 offset7">
	<?php echo $this->pagination->create_links(); ?>
	</div>
</div>

<?php else : ?>
<p>
<?php echo lang('common_none'); ?>
</p>
<?php endif; ?>