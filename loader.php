<?php ///display:none
if(isset($top,$top))
	$style="position: fixed; z-index: 100; left: $left%; top: $top%; display:none;";
else 
	$style="position: fixed; left: 50%; top: 10%; z-index: 100; display:none"
?>
<div id='ajax_loader' style='<?php echo $style; ?>'>
	<button class="btn btn-primary" type="button" disabled>
		<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
		Loading...
	</button>  
</div> 