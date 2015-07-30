
<div class="row collapse">
	<div class="small-10 columns">
		<select id="select-item">
			<?php
			// render _authitem_opt ;
			$this->renderPartial('../item/_authitem_opt', array(
				 'authItem' => $authItem
			));
			?>
		</select>
	</div>
	<div class="small-2 columns">
		<a href="#" id="tombol-assign" class="button postfix">Assign</a>
	</div>
</div>

<script>
	$("#tombol-assign").click(function() {
		console.log(jQuery("#select-item").val());
		dataString = 'item=' + jQuery("#select-item").val();
		$.fn.yiiGridView.update('auth-assigned-grid', {
			type: 'POST',
			data: dataString,
			url: "<?php echo $this->createUrl('assign', array('userid' => $user->id)); ?>",
			success: function() {
				$.fn.yiiGridView.update('auth-assigned-grid');
				updateItemOpt();
			}
		});
		return false;
	});
	function updateItemOpt() {
		$("#select-item").load("<?php echo $this->createUrl('listauthitem', array('userid' => $user->id)); ?>");
	}
</script>