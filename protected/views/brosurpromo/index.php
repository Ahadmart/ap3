<?php
/* @var $this BrosurpromoController */

$this->breadcrumbs = array(
	'Brosurpromo',
);
$this->boxHeader['small'] = 'Brosur Promo';
$this->boxHeader['normal'] = 'Brosur Promo';
?>

<div class="row">
	<div class="small-12 column">
		<?php
		/*
		<ul class="clearing-thumbs small-block-grid-1" data-clearing>
			<li>
				<a href="assets/brosurpromo/brosur 1.png"><img src="assets/brosurpromo/brosur 1-th.png"></a>
				<a class="tiny bigfont button">Ganti</a>
			</li>
			<li>
				<a href="assets/brosurpromo/brosur 2.jpg"><img src="assets/brosurpromo/brosur 2-th.jpg"></a>
				<a class="tiny bigfont button">Ganti</a>
			</li>
			<li>
				<a href="assets/brosurpromo/brosur 3.jpg"><img src="assets/brosurpromo/brosur 3-th.jpg"></a>
				<a class="tiny bigfont button">Ganti</a>
			</li>
		</ul>
		*/
		?>
		<table class="tabel-index">
			<thead>
				<th style="text-align:center">Thumbnail</th>
				<th style="text-align:center">Aksi</th>
			</thead>
			<tbody>

				<tr>
					<td style="text-align:center">
						<a href="<?= $assetsPath ?>brosur 1.png"><img src="<?= $assetsPath ?>brosur 1-th.png"></a>
					</td>
					<td style="text-align:center">
						<form action="/upload" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="small-12 column">
									<input class="tiny bigfont secondary button" name="file-gambar" type="file" />
								</div>
							</div>
							<div>
								<div class="small-12 column">
									<input class="tiny bigfont expand button" name="upload-gambar" type="submit" value="Upload">
								</div>
							</div>
						</form>
					</td>
				</tr>
				<tr>
					<td style="text-align:center">
						<a href="<?= $assetsPath ?>brosur 2.jpg"><img src="<?= $assetsPath ?>/brosur 2-th.jpg"></a>
					</td>
					<td style="text-align:center">
						<form action="/upload" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="small-12 column">
									<input class="tiny bigfont secondary button" name="file-gambar" type="file" />
								</div>
							</div>
							<div>
								<div class="small-12 column">
									<input class="tiny bigfont expand button" name="upload-gambar" type="submit" value="Upload">
								</div>
							</div>
						</form>
					</td>
				</tr>

				<tr>
					<td style="text-align:center">
						<a href="<?= $assetsPath ?>brosur 3.jpg"><img src="<?= $assetsPath ?>brosur 3-th.jpg"></a>
					</td>
					<td style="text-align:center">
						<form action="/upload" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="small-12 column">
									<input class="tiny bigfont secondary button" name="file-gambar" type="file" />
								</div>
							</div>
							<div>
								<div class="small-12 column">
									<input class="tiny bigfont expand button" name="upload-gambar" type="submit" value="Upload">
								</div>
							</div>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>