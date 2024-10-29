<?php
/* @var $this SkutransferController */
/* @var $model SkuTransfer */

$this->breadcrumbs = [
    'Sku Transfer' => ['index'],
    $model->id     => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "Sku Transfer: {$model->sku->nama}";
?>
<div class="row">
    <div class="large-6 columns">
        <h4>Dari Barang</h4>
        <?php $this->renderPartial('_ubah_dari', [
            'model'      => $model,
            'barangAsal' => $barangAsal,
        ]); ?>
    </div>
    <div class="large-6 columns">
        <h4>Ke Barang</h4>
        <div id="tujuan-container">
            <?php $this->renderPartial('_ubah_ke', [
                'model'        => $model,
                'barangTujuan' => $barangTujuan,
            ]); ?>
        </div>
    </div>
    <div class="small-12 columns">
        <h4>Qty Transfer</h4>
        <div class="panel">
            <div id="qty-transfer" class="row">
                <div class="medium-6 columns">
                    <div class="row collapse">
                        <div class="small-4 columns">
                            <?= CHtml::numberField('qtyasal', '1', ['disabled' => 'disabled']) ?>
                        </div>
                        <div class="small-1 columns">
                            <span class="postfix" id="satuanasal">pcs</span>
                        </div>
                        <div class="small-2 columns">
                            <span class="prefix postfix"><i class="fa fa-arrow-right fa-2x"></i></span>
                        </div>
                        <div class="small-4 columns">
                            <?= CHtml::textField('qtytujuan', '1', ['disabled' => 'disabled']) ?>
                        </div>
                        <div class="small-1 columns">
                            <span class="postfix" id="satuantujuan">pcs</span>
                        </div>
                    </div>
                </div>
                <div class="medium-6 columns rata-kanan">
                    <button class="tiny bigfont button" id="t-transfer" disabled="disabled">Transfer</button>
                </div>
            </div>
        </div>
    </div>
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
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>

<script>
    let kumulatifRasioKonversi = 1;
    let asalId;
    let tujuanId;
    <?php
    /*
$('input[name="selected_dari"').on('change', function() {
console.log($("input[name='selected_dari']:checked").val())
})

$('input[name="selected_ke"').on('change', function() {
console.log($("input[name='selected_ke']:checked").val())
})
 */
    ?>

    function asalDipilih(id) {
        var dariId = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(dariId) || !dariId.length) {
            // console.log("dariId tidak dipilih");
        } else {
            // console.log("dariId: " + dariId[0])
            $("#tujuan-container").load("<?= $this->createUrl('rendertujuan') ?>", {
                dariId: dariId[0]
            });
            $("#qtyasal").prop("disabled", true);
            $("#t-transfer").prop("disabled", true);
        }
    }

    function tujuanDipilih(id) {
        var keId = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(keId) || !keId.length) {
            // console.log("keId tidak dipilih");
        } else {
            // console.log("keId: " + keId[0])
            updateQtyTransfer();

        }
    }

    function updateQtyTransfer() {
        var dariId = $('#barang-asal-grid').yiiGridView('getSelection');
        var keId = $('#barang-tujuan-grid').yiiGridView('getSelection');
        if ((Array.isArray(dariId) && dariId.length) && (Array.isArray(keId) && keId.length)) {
            // console.log('Ready to transfer dari: ' + dariId[0] + ' ke: ' + keId[0]);
            var dataKirim = {
                'asalId': dariId[0],
                'tujuanId': keId[0]
            };
            $.ajax({
                type: "POST",
                url: '<?php echo $this->createUrl('konversi'); ?>',
                data: dataKirim,
                dataType: "json",
                success: function(data) {
                    if (data.sukses) {
                        kumulatifRasioKonversi = data.rasioKonversi;
                        isiData(data)
                    }
                }
            });
        }
    }

    function isiData(data) {
        asalId = data.asal;
        tujuanId = data.tujuan;
        jml = $("#qtyasal").val() * kumulatifRasioKonversi;
        $("#qtytujuan").val(jml);
        $("#satuanasal").html(data.satuanAsal);
        $("#satuantujuan").html(data.satuanTujuan);
        $("#qtyasal").prop("disabled", false);
        $("#t-transfer").prop("disabled", false);
    }

    $("#qtyasal").on('input', function() {
        jml = $("#qtyasal").val() * kumulatifRasioKonversi;
        $("#qtytujuan").val(jml);
    })

    $("#t-transfer").on('click', function() {
        var dataKirim = {
            'asalId': asalId,
            'tujuanId': tujuanId,
            'qty': $("#qtyasal").val()
        };
        $.ajax({
            type: "POST",
            url: '<?php echo $this->createUrl('transfer', ['id' => $model->id]); ?>',
            data: dataKirim,
            dataType: "json",
            success: function(data) {
                if (data.sukses) {

                }
            }
        });
    })
</script>