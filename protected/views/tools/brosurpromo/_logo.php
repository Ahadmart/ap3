<?php
foreach ($imgs as $img) {
?>
	<div class="logo-card">
		<div class="img">
			<a href="<?= $this->createUrl($assetsPath . $img['filename']) ?>"><img src="<?= $this->createUrl($assetsPathTh . $img['filename']) ?>"></a>
		</div>
		<a class="tiny bigfont warning button tombol-hapus-logo" data-filename="<?= $img['filename'] ?>"><i class="fa fa-times"></i></a>
	</div>
<?php
}
?>
<script>
	$(".tombol-hapus-logo").click(function() {
		console.log($(this).data('filename'))
		var dataKirim = {
			filename: $(this).data('filename'),
		};
		$.ajax({
			type: "POST",
			url: '<?php echo $this->createUrl('hapus', ['type' => AhadPosWsClient::TIPE_LOGO_UPDATE]); ?>',
			data: dataKirim,
			dataType: "json",
			success: function(data) {
				if (data.sukses) {
					$(".logo-container").load('<?= $this->createUrl('loadlogo') ?>');
				}
			}
		});
		return false;
	})
</script>