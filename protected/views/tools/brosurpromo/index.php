<?php
/* @var $this BrosurpromoController */

$this->breadcrumbs = [
	'Brosurpromo',
];
$this->boxHeader['small']  = 'Brosur Promo';
$this->boxHeader['normal'] = 'Brosur Promo';
?>
<style>
	.brosur-card-container,
	.logo-container {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		gap: 20px;
		padding: 20px 0;
	}

	.brosur-card,
	.logo-card {
		width: 203px;
		height: 203px;
		border: thin solid #002c5e;
		border-radius: 5px;
	}

	.brosur-card .img,
	.logo-card .img {
		height: 200px;
		width: 200px;
		display: flex;
		align-items: center;
		/* border: thin solid white; */
	}

	.img>a {
		margin: auto;
	}

	.tombol-hapus,
	.tombol-hapus-logo {
		top: -97%;
		left: 72%;
	}

	input[type="file"] {
		display: none;
	}

	.custom-upload {
		font-size: 0.875rem;
	}
</style>
<!-- <div class="row">
	<div class="small-12 column">
		<div class="panel">
			<p>Drop image here</p>
		</div>
	</div>
</div> -->
<div class="row">
	<div class="small-12 column">
		<form id="upload-logo" action="<?= $this->createUrl('uploadlogo') ?>" method='POST' enctype="multipart/form-data">
			<input type="file" name="gambar-logo" id="filelogo" accepts="image/*" />
		</form>
		<form id="upload-brosur" action="<?= $this->createUrl('uploadbrosur') ?>" method='POST' enctype="multipart/form-data">
			<input type="file" name="gambar-brosur" id="file" multiple="true" accepts="image/*" />
		</form>
	</div>
</div>
<div class="row">
	<div class="small-12 column">
		<div class="logo-container">
			<?php
			$this->renderPartial('_logo', [
				'assetsPath'   => $assetsPath,
				'assetsPathTh' => $assetsPathTh,
				'imgs'         => $logos,
			]);
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 column">
		<div class="brosur-card-container">
			<?php
			$this->renderPartial('_brosur', [
				'assetsPath'   => $assetsPath,
				'assetsPathTh' => $assetsPathTh,
				'imgs'         => $imgs,
			]);
			?>
		</div>
	</div>
</div>
<script>
	function showFilesGanti(name) {
		$("input[name='" + name + "']").click();
		return false;
	}
	$(document).on("change", "input[name^='gambar-logo']", function(e) {
		e.preventDefault();
		$("#upload-logo").trigger('submit')
	})
	$(document).on("change", "input[name^='gambar-brosur']", function(e) {
		e.preventDefault();
		$("#upload-brosur").trigger('submit')
	})
	$("#upload-logo").submit(function(e) {
		e.preventDefault();
		var data = new FormData($("#upload-logo")[0]);
		uploadFile('<?= $this->createUrl('uploadlogo') ?>', data);
	})
	$("#upload-brosur").submit(function(e) {
		e.preventDefault();
		var data = new FormData();
		jQuery.each(jQuery('#file')[0].files, function(i, file) {
			data.append('brosur' + i, file);
		});
		uploadFile('<?= $this->createUrl('uploadbrosur') ?>', data);
	})

	function uploadFile(uploadurl, fileuploaddata) {
		console.log('AJAX Upload');
		$.ajax({
			// xhr: function() {
			// 	var xhr = new window.XMLHttpRequest();

			// 	xhr.upload.addEventListener("progress", function(evt) {
			// 		if (evt.lengthComputable) {
			// 			var percentComplete = evt.loaded / evt.total;
			// 			percentComplete = parseInt(percentComplete * 100);
			// 			console.log(percentComplete);

			// 			if (percentComplete === 100) {

			// 			}

			// 		}
			// 	}, false);

			// 	return xhr;
			// },
			url: uploadurl,
			type: "POST",
			data: fileuploaddata,
			processData: false,
			contentType: false,
			success: function(result) {					
				$(".logo-container").load('<?= $this->createUrl('loadlogo') ?>');
				$(".brosur-card-container").load('<?= $this->createUrl('loadbrosur') ?>');
			}
		});
	}
</script>

<?php
$this->menu = [
	['itemOptions' => ['class' => 'divider'], 'label' => ''],
	[
		'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => '',
		'items'          => [

			['label' => '<i class="fa fa-plus"></i> Upload <span class="ak">L</span>ogo', 'url' => '', 'linkOptions' => [
				'class'     => 'button',
				'accesskey' => 'u',
				'onclick'   => "return showFilesGanti('gambar-logo')",
			]],
			['label' => '<i class="fa fa-plus"></i> <span class="ak">U</span>pload Brosur', 'url' => '', 'linkOptions' => [
				'class'     => 'button',
				'accesskey' => 'u',
				'onclick'   => "return showFilesGanti('gambar-brosur')",
			]],
		],
		'submenuOptions' => ['class' => 'button-group'],
	],
	[
		'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => '',
		'items'          => [
			['label' => '<i class="fa fa-plus"></i> Logo', 'url' => '', 'linkOptions' => [
				'class'   => 'button',
				'onclick' => "return showFilesGanti('gambar-logo')",
			]],
			['label' => '<i class="fa fa-plus"></i> Brosur', 'url' => '', 'linkOptions' => [
				'class'   => 'button',
				'onclick' => "return showFilesGanti('gambar-brosur')",
			]],
		],
		'submenuOptions' => ['class' => 'button-group'],
	],
];
?>