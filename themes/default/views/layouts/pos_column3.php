<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
	<div id="logo">
		<img src="<?php echo Yii::app()->theme->baseUrl.'/img/' ?>ahadmart-logo-d.png" />
	</div>
	<div style="display:none" id="info">
		<table id="info-tabel">
			<tbody>
				<tr>
					<td class="key">Struk# :</td>
					<td>1234567</td>
				</tr>
				<tr>
					<td class="key">Cust :</td>
					<td>PD. KACANG</td>
				</tr>
				<tr>
					<td class="key">Kasir :</td>
					<td>Nur</td>
				</tr>
			</tbody>
		</table>
	</div>
	<form>
		<div class="row">
			<label for="customer"><kbd>Alt</kbd> <kbd>p</kbd></label>
		</div>
		<div class="row collapse">
			<div class="small-9 columns">
				<?php
				echo CHtml::dropDownList('customer', '', CHtml::listData(Customer::model()->findAll(), 'id', 'nama'), array(
					 'id' => 'customer',
					 'accesskey' => 'p',
				));
				?>
			</div>
			<div class="small-3 columns">
				<a href="#" class="success button postfix" id="start"><i class="fa fa-plus"></i></a>
			</div>
		</div>
	</form>
	<a href="<?php echo Yii::app()->baseUrl; ?>" class="secondary expand button"><i class="fa fa-home fa-2x fa-fw"></i></a>
	<a href="<?php echo Yii::app()->createUrl('/customer'); ?>" class="secondary expand button"><i class="fa fa-user fa-2x fa-fw"></i></a>
</div>
<div class="medium-7 columns">
	<div id="transaksi">
		<?php echo $content; ?>
	</div>
</div>
<div class="medium-3 columns sidebar kanan">
	<form>
		<div class="row collapse">
			<div class="small-3 large-2 columns">
				<span class="prefix"><i class="fa fa-barcode fa-2x"></i></span>
			</div>
			<div class="small-9 large-10 columns">
				<input id="scan" type="text"  placeholder="Scan [B]arcode" accesskey="b"/>
			</div>
		</div>
		<div class="row collapse">
			<div class="small-3 large-2 columns">
				<span class="prefix huruf"><b>Q</b>ty</span>
			</div>
			<div class="small-6 large-7 columns">
				<input type="text"  value="1" placeholder="[Q]ty" accesskey="q"/>
			</div>
			<div class="small-3 large-3 columns">
				<a href="#" class="button postfix">Tambah</a>
			</div>
		</div>
	</form>
	<form>
		<div class="row collapse">
			<div class="small-3 large-2 columns">
				<span class="prefix"><i class="fa fa-search fa-2x"></i></span>
			</div>
			<div class="small-6 large-7 columns">
				<input type="text"  placeholder="[C]ari Barang" accesskey="c"/>
			</div>
			<div class="small-3 large-3 columns">
				<a href="#" class="button postfix">Cari</a>
			</div>
		</div>
	</form>
	<div id="total-belanja">
		1.234.456.789
	</div>
	<div id="surcharge">
		654.321
	</div>
	<div id="kembali">
		987.654
	</div>
	<div class="row collapse">
		<div class="small-3 large-2 columns">
			<span class="prefix"><i class="fa fa-2x fa-bars"></i></span>
		</div>
		<div class="small-6 large-7 columns">
			<select accesskey="a">
				<option value="1">Cash</option>
				<option value="2">Transfer</option>
				<option value="3">Debit</option>
				<option value="4">Kredit</option>
			</select>
		</div>
		<div class="small-3 large-3 columns">
			<span class="postfix"><kbd>Alt</kbd> <kbd>a</kbd></span>
		</div>
	</div>	
	<div class="row collapse">
		<div class="small-3 large-2 columns">
			<span class="prefix"><i class="fa fa-credit-card fa-2x"></i></span>
		</div>
		<div class="small-6 large-8 columns">
			<input type="text"  placeholder="Surcharge"/>
		</div>
		<div class="small-3 large-2 columns">
			<span class="postfix huruf">%</span>
		</div>
	</div>
	<div class="row collapse">
		<div class="small-3 large-2 columns">
			<span class="prefix huruf">IDR</span>
		</div>
		<div class="small-9 large-10 columns">
			<input type="text"  placeholder="[U]ang Dibayar" accesskey="u"/>
		</div>
	</div>
	<a href="" class="button" id="tombol-simpan">Simpan</a>
	<a href="" class="secondary button" id="tombol-batal">Batal</a>
</div>
<script>
	$("#start").click(function() {
		function init() {
			$("#info").show(500);
		}
		var datakirim = {
			"start": true,
			"customerId": $("#customer").val(),
		};
		var dataurl = "<?php echo $this->createUrl('start'); ?>";
		$.ajax({
			data: datakirim,
			url: dataurl,
			type: "POST",
			success: function(data) {
				var respon = JSON && JSON.parse(data) || $.parseJSON(data);
				if (respon.sukses) {
					init();
				}
			}
		});
		$("#scan").focus();
	})
</script>
<?php $this->endContent(); ?>