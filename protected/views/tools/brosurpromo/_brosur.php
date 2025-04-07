<?php
foreach ($imgs as $img) {
?>
	<div class="brosur-card">
		<div class="img">
			<a href="<?= $this->createUrl($assetsPath . $img['filename']) ?>"><img src="<?= $this->createUrl($assetsPathTh . $img['filename']) ?>"></a>
		</div>
		<a class="tiny bigfont warning button tombol-hapus" data-filename="<?= $img['filename'] ?>"><i class="fa fa-times"></i></a>
	</div>
<?php
}
?>
<script>
	$(".tombol-hapus").click(function() {
		console.log($(this).data('filename'))
		var dataKirim = {
			filename: $(this).data('filename'),
		};
		$.ajax({
			type: "POST",
			url: '<?php echo $this->createUrl('hapus', ['type' => AhadPosWsClient::TIPE_BROSUR_UPDATE]); ?>',
			data: dataKirim,
			dataType: "json",
			success: function(data) {
				if (data.sukses) {
					$(".brosur-card-container").load('<?= $this->createUrl('loadbrosur') ?>');
				}
			}
		});
		return false;
	})
</script>