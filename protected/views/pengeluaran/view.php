<?php
/* @var $this PengeluaranController */
/* @var $model Pengeluaran */

$this->breadcrumbs = array(
	 'Pengeluaran' => array('index'),
	 $model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = 'Pengeluaran: '.$model->nomor;
?>
<div class="row">
	<div class="small-12 columns header">
		<span class="secondary label">Kepada</span><span class="label"><?php echo $model->profil->nama; ?></span>
		<span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
		<span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
		<span class="secondary label">Total</span><span class="alert label"><?php echo $model->total; ?></span>
		<span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>
	</div>
</div>
<div class="row">
	<div class="small-12 columns header">
		<span class="secondary label">Jenis Trx</span><span class="label"><?php echo $model->jenisTransaksi->nama; ?></span>
		<span class="secondary label">Keterangan</span><span class="label"><?php echo!empty($model->keterangan) ? $model->keterangan : '-'; ?></span>
		<span class="secondary label">Kategori</span><span class="label"><?php echo $model->kategori->nama; ?></span>
		<span class="secondary label">Kas/Bank</span><span class="label"><?php echo $model->kasBank->nama; ?></span>
	</div>
</div>
<?php
$this->widget('BGridView', array(
	 'id' => 'pengeluaran-detail-grid',
	 'dataProvider' => $detail->search(),
	 'summaryText' => '{start}-{end} dari {count}',
	 'filter' => $detail,
	 'columns' => array(
		  array(
				'name' => 'namaItem',
				'value' => '$data->item->nama'
		  ),
		  array(
				'name' => 'nomorDokumenHutangPiutang',
				'value' => 'is_null($data->hutangPiutang) ? "" : $data->hutangPiutang->nomor'
		  ),
		  'keterangan',
		  array(
				'name' => 'jumlah',
            'value' => 'number_format($data->jumlah, 0, ",", ".")',
				'headerHtmlOptions' => array('class' => 'right'),
				'htmlOptions' => array('class' => 'right')
		  ),
	 ),
));
?>

<?php
$this->menu = array(
	 array('itemOptions' => array('class' => 'divider'), 'label' => false),
	 array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
		  'items' => array(
				array('label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
						  'class' => 'button',
						  'accesskey' => 'u'
					 )),
				array('label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
						  'class' => 'alert button',
						  'accesskey' => 'h',
						  'submit' => array('hapus', 'id' => $model->id),
						  'confirm' => 'Anda yakin?'
					 )),
				array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
						  'class' => 'success button',
						  'accesskey' => 'i'
					 ))
		  ),
		  'submenuOptions' => array('class' => 'button-group')
	 ),
	 array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
		  'items' => array(
				array('label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
						  'class' => 'button',
					 )),
				array('label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
						  'class' => 'alert button',
						  'submit' => array('hapus', 'id' => $model->id),
						  'confirm' => 'Anda yakin?'
					 )),
				array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
						  'class' => 'success button',
					 ))
		  ),
		  'submenuOptions' => array('class' => 'button-group')
	 )
);
