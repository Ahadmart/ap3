<?php
/* @var $this ProfilController */
/* @var $model Profil */

$this->breadcrumbs = [
    'Profil' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Profil: ' . $model->nama;
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BDetailView', [
            'data'       => $model,
            'attributes' => [
                [
                    'name'  => 'tipe.nama',
                    'label' => 'Tipe'
                ],
                'nama',
                'nomor',
                'identitas',
                'npwp:npwp',
                'alamat1',
                'alamat2',
                'alamat3',
                'telp',
                'hp',
                [
                    'label' => 'Jenis Kelamin',
                    'value' => is_null($model->jenis_kelamin) ? '' : $model->listJenisKelamin()[$model->jenis_kelamin]
                ],
                'tanggal_lahir',
                'surel',
                'keterangan',
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'u'
            ]],
            ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'alert button',
                'accesskey' => 'h',
                'submit'    => ['hapus', 'id' => $model->id],
                'confirm'   => 'Anda yakin?'
            ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', ['id' => $model->id]), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'   => 'alert button',
                'submit'  => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?'
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
?>