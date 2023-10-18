<?php
/* @var $this PenerimaanController */
/* @var $model Penerimaan */

$this->breadcrumbs = [
    'Penerimaan' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Penerimaan: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Kepada</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Jenis Trx</span><span class="label"><?php echo $model->jenisTransaksi->nama; ?></span>
        <span class="secondary label">Keterangan</span><span class="label"><?php echo !empty($model->keterangan) ? $model->keterangan : '-'; ?></span>
        <span class="secondary label">Kategori</span><span class="label"><?php echo $model->kategori->nama; ?></span>
        <span class="secondary label">Kas/Bank</span><span class="label"><?php echo $model->kasBank->nama; ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label label-total">Total</span><span class="alert label label-total"><?php echo $model->total; ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'           => 'penerimaan-detail-grid',
            'dataProvider' => $detail->search(),
            'summaryText'  => '{start}-{end} dari {count}, Total: <span class="label-total">' . $model->total . '</span>',
            //filter' => $model,
            'columns'      => [
                [
                    'name'  => 'namaItem',
                    'value' => '$data->item->nama',
                ],
                //'nomor_dokumen',
                [
                    'name'  => 'nomorDokumenHutangPiutang',
                    'value' => 'is_null($data->hutangPiutang) ? "" : $data->hutangPiutang->nomor',
                ],
                'keterangan',
                [
                    'name'              => 'jumlah',
                    'value'             => 'number_format($data->jumlah, 0 ,",", ".")',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>