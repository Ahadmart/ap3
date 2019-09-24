<?php
/* @var $this KasirController */
/* @var $model Kasir */

$this->breadcrumbs = [
    'Kasir' => ['index'],
    'Rekap',
];

$this->boxHeader['small']  = 'Rekap';
$this->boxHeader['normal'] = 'Rekap Kasir';
?>

<div class="row">
    <div class="small-12 columns">
        <div class="panel" style="overflow: visible">
            <pre><?php echo $text; ?></pre>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        echo CHtml::link('<i class="fa fa-times"></i> Kemba<span class="ak">l</span>i', $this->createUrl('index'), [
            'class'     => 'secondary tiny bigfont button',
            'accesskey' => 'l'
        ]);
        ?>
               <ul class="button-group right">
            <li>
                <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
                <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printers as $printer) {
                        ?>
                        <?php
                        if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                            /* Jika printer pdf, tambahkan pilihan ukuran kertas */
                            ?>
                            <span class="sub-dropdown"><?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></span>
                            <ul>
                                <?php
                                foreach ($kertasPdf as $key => $value):
                                    ?>
                                    <li><a target="blank" class="tombol-cetak" href="<?=
                                        $this->createUrl('cetak', [
                                            'printId' => $printer['id'],
                                            'kertas' => $key,
                                        ])
                                        ?>"><?= $value; ?></a></li>
                                        <?php
                                    endforeach;
                                    ?>
                            </ul>
                            <?php
                        } else {
                            ?>
                            <li>
                                <a class="tombol-cetak" href="<?=
                                   $this->createUrl('cetak', [
                                       'id' => $model->id,
                                       'printId' => $printer['id'],
                                   ])
                                   ?>">
                                    <?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>  
    </div>
</div>

<?php
$this->menu                = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions'    => ['class' => 'has-form hide-for-small-only'],
        'label'          => false,
        'items'          => [
            ['label'       => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex',
                'url'         => $this->createUrl('index'),
                'linkOptions' => [
                    'class'     => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions'    => ['class' => 'has-form show-for-small-only'],
        'label'          => false,
        'items'          => [
            ['label'       => '<i class="fa fa-asterisk"></i>',
                'url'         => $this->createUrl('index'),
                'linkOptions' => [
                    'class' => 'success button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
