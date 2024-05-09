<?php
/* @var $this BrosurpromoController */

$this->breadcrumbs = array(
	'Brosurpromo',
);
$this->boxHeader['small'] = 'Brosur Promo';
$this->boxHeader['normal'] = 'Brosur Promo';
?>
<style>
	.brosur-card-container {
		display: flex;
		gap: 20px;
	}

	.brosur-card {
		width: 200px;
		/* height: 400px; */
	}

	.brosur-card .img {
		height: 200px;
		width: 200px;
		display: flex;
		align-items: center;
	}

	.tombol-brosur {
		display: flex;
		flex-direction: row;
		gap: 2%;
	}

	.tombol-brosur>a,
	.tombol-brosur>label {
		width: 49%;
	}

	input[type="file"] {
		display: none;
	}

	.custom-upload {
		font-size: 0.875rem;
	}
</style>

<div class="row">
	<div class="small-12 column">
		<div class="brosur-card-container">
			<?php
			foreach ($imgs as $img) {
			?>
				<div class="brosur-card">
					<div class="img">
						<a href="<?= $assetsPath . $img['filename'] ?>"><img src="<?= $assetsPathTh . $img['filename'] ?>"></a>
					</div>
					<div class="tombol-brosur">
						<a class="tiny bigfont button">Hapus</a>
						<label for="file_<?= $img['filename'] ?>" class="custom-upload" onclick="showFilesGanti('file_<?= $img['filename'] ?>')">Ganti</label>
					</div>
					<form action="<?= $this->createUrl('upload', ['asal' => $img['filename']]) ?>" method='POST' enctype="multipart/form-data">
						<input name="file_<?= $img['filename'] ?>" type="file" onchange="this.form.submit()" />
					</form>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>
<script>
	function showFilesGanti(name) {
		$("input[name='" + name + "']").click();
	}
</script>