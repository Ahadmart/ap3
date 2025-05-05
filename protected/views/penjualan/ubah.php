<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = [
    'Penjualan' => ['index'],
    $model->id  => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Penjualan';
?>
<div id="hapus-form" class="small reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <h2 id="modalTitle">Konfirmasi Hapus</h2>
    <label>Alasan penghapusan:
        <input type="text" id="alasan-hapus">
    </label>
    <div class="text-right">
        <a href="#" class="small bigfont tiny button alert" id="hapus-submit">OK</a>
        <a class="close-reveal-modal">&#215;</a>
    </div>
</div>

<div class="row">
    <div class="large-7 columns header">
        <?php
        if ($model->transfer_mode) {
        ?>
            <span class="warning label">Transfer Mode</span>
        <?php
        }
        ?>
        <span class="secondary label">Customer</span><span class="label"><?php echo $model->profil->nama; ?></span><br />
        <span class="secondary label label-total">Total</span><span class="label label-total" id="total-penjualan"><?php echo $model->total; ?></span>
    </div>
    <div class="large-5 columns">
        <ul class="button-group right">
            <li>
                <button href="#" accesskey="p" data-dropdown="printinvoice" aria-controls="printinvoice" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text fa-fw"></i> <span class="ak">P</span>rint Draft Invoice (rrp)</button><br>
                <ul id="printinvoice" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerInvoiceRrp as $printer) {
                    ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printdraftinvoice', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <?php
                echo CHtml::ajaxLink(
                    '<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Penjualan',
                    $this->createUrl('simpanpenjualan', ['id' => $model->id]),
                    [
                        'data'    => 'simpan=true',
                        'type'    => 'POST',
                        'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();;
                            }
                        }',
                    ],
                    [
                        'class'     => 'tiny bigfont button',
                        'accesskey' => 's',
                    ]
                );
                ?>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <?php
    $this->renderPartial('_input_detail', [
        'penjualan' => $model,
    ]);
    ?>
</div>
<div class="row" id="penjualan-detail">
    <?php
    $this->renderPartial('_detail', [
        'penjualan'             => $model,
        'penjualanDetail'       => $penjualanDetail,
        'konfirmasiHapusDetail' => $konfirmasiHapusDetail,
    ]);
    ?>
</div>
<div class="row" id="barang-list" style="display:none">
    <?php
    $this->renderPartial('_barang_list', [
        'barang' => $barang,
    ]);
    ?>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'],
        'label'          => false,
        'items'          => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't',
            ]],
            [
                'label'       => '<i class="fa fa-times"></i> <span class="ak">H</span>apus',
                'url'         => $this->createUrl('hapus', ['id' => $model->id]),
                'linkOptions' => [
                    'class'     => 'alert button tombol-hapus-nota',
                    'accesskey' => 'h',
                    // 'submit'    => ['hapus', 'id' => $model->id],
                    'confirm'   => $konfirmasiHapusNota ? null : 'Anda yakin?',
                ],
            ],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'],
        'label'          => false,
        'items'          => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
            [
                'label'       => '<i class="fa fa-times"></i>',
                'url'         => $this->createUrl('hapus', ['id' => $model->id]),
                'linkOptions' => [
                    'class'   => 'alert button tombol-hapus-nota',
                    // 'submit'  => ['hapus', 'id' => $model->id],
                    'confirm' => $konfirmasiHapusNota ? null : 'Anda yakin?',
                ],
            ],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>

<script>
    var deleteUrl = null;

    $(document).on('click', '.tombol-hapus-nota', function(e) {

        deleteUrl = $(this).attr('href'); // simpan URL
        e.preventDefault();

        <?php
        if ($konfirmasiHapusNota) {
        ?>
            $('#hapus-form').one('opened.fndtn.reveal', function() {
                $('#alasan-hapus').val('').focus();
            });

            $("#alasan-hapus").val('').focus(); // reset and focus input
            $("#hapus-form").foundation('reveal', 'open');
        <?php
        } else {
        ?>
            $.ajax({
                type: 'GET',
                url: deleteUrl,
                success: function(data) {
                    if (data.sukses) {
                        window.location.href = "<?php echo $this->createUrl('index') ?>"
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                        });
                    }
                }
            });
        <?php
        }
        ?>
    });


    $("#alasan-hapus").keydown(function(e) {
        if (e.keyCode === 13) {
            $("#hapus-submit").click();
        }
    });

    // Saat submit di modal diklik, kirim AJAX
    $("#hapus-submit").click(function() {
        var alasan = $("#alasan-hapus").val().trim();
        // if (alasan === "") {
        //     alert("Silakan isi alasan penghapusan.");
        //     return;
        // }

        if (!deleteUrl) {
            alert("URL penghapusan tidak ditemukan.");
            return;
        }

        $.ajax({
            type: 'POST',
            url: deleteUrl,
            data: {
                alasan: alasan
            },
            success: function(data) {
                if (data.sukses) {
                    window.location.href = "<?php echo $this->createUrl('index') ?>"
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                }
            }
        });

        $("#hapus-form").foundation('reveal', 'close');
        deleteUrl = null;
        return false;
    });
    // Saat tombol hapus diklik, tampilkan dialog
</script>