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

$this->pageTitle = Yii::app()->name . ' - ' . $this->boxHeader['normal'];
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
            ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'alert button',
                'accesskey' => 'h',
                'submit'    => ['hapus', 'id' => $model->id],
                'confirm'   => 'Anda yakin?',
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
            ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'   => 'alert button',
                'submit'  => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    class Pecahan {
        constructor(pembilang, penyebut = 1) {
            if (penyebut === 0) {
                throw new Error('Penyebut tidak boleh 0')
            }

            const fpb = Pecahan.#fpb(pembilang, penyebut)
            pembilang /= fpb;
            penyebut /= fpb;

            if (penyebut < 0) {
                pembilang = -pembilang;
                penyebut = -penyebut;
            }
            this.pembilang = pembilang;
            this.penyebut = penyebut;
        }

        static #fpb(a, b) {
            a = Math.abs(a);
            b = Math.abs(b);
            while (b !== 0) {
                [a, b] = [b, a % b]
            }
            return a
        }

        toString() {
            return this.penyebut === 1 ? `${this.pembilang}` : `${this.pembilang}/${this.penyebut}`;
        }

        static buatPecahan(text) {
            text = String(text).trim();
            if (text.includes('/')) {
                const [pembilang, penyebut] = text.split('/')
                return new Pecahan(parseInt(pembilang), parseInt(penyebut))
            } else {
                return new Pecahan(parseInt(text), 1)
            }
        }

        multiply(pecahan) {
            return new Pecahan(this.pembilang * pecahan.pembilang, this.penyebut * pecahan.penyebut);
        }
    }
</script>
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
        pecahanAsal = Pecahan.buatPecahan($("#qtyasal").val())
        pecahanKRK = Pecahan.buatPecahan(kumulatifRasioKonversi);
        // jml = $("#qtyasal").val() * kumulatifRasioKonversi;
        // asal = new Pecahan($("#qtyasal").val());
        hasil = pecahanAsal.multiply(pecahanKRK);
        $("#qtytujuan").val(hasil.toString());
        $("#satuanasal").html(data.satuanAsal);
        $("#satuantujuan").html(data.satuanTujuan);
        $("#qtyasal").prop("disabled", false);
        $("#t-transfer").prop("disabled", false);
    }

    $("#qtyasal").on('input', function() {
        pecahanAsal = Pecahan.buatPecahan($("#qtyasal").val())
        console.log('Pecahan Asal: '+ pecahanAsal.toString())
        pecahanKRK = Pecahan.buatPecahan(kumulatifRasioKonversi);
        console.log('Pecahan KRK: '+ pecahanKRK.toString())
        hasil = pecahanAsal.multiply(pecahanKRK);
        $("#qtytujuan").val(hasil.toString());
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
                    window.location.replace("<?php echo $this->createUrl('index'); ?>");
                } else {

                    $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                            //class_name: 'gritter-center'
                        });
                }
            }
        });
    })
</script>