<?php
/* @var $this PosController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Penjualan: {$model->nomor}";
?>

<div class="medium-6 large-7 columns" style="/*height: 100%; overflow: scroll*/">
    <div id="transaksi">
        <?php
        $this->renderPartial('_detail', array(
            'penjualan' => $model,
            'penjualanDetail' => $penjualanDetail
        ));
        ?>
    </div>
</div>
<div class="medium-4 large-3 columns sidebar kanan">
    <div id="total-belanja">
        <?php echo $model->getTotal(); ?>
    </div>
    <div id="kembali">
        0
    </div>
    <div class="row collapse">
        <div class="small-3 large-2 columns">
            <span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
        </div>
        <div class="small-6 large-8 columns">
            <input id="scan" type="text"  placeholder="Scan [B]arcode / Input nama" accesskey="b" autofocus="autofocus"/>
        </div>
        <div class="small-3 large-2 columns">
            <a href="#" class="button postfix" id="tombol-tambah-barang"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
        </div>
    </div>
    <div class="row collapse">
        <?php /* Company account */ ?>
        <div class="small-3 large-2 columns">
            <span class="prefix"><i class="fa fa-2x fa-square"></i></span>
        </div>
        <div class="small-6 large-7 columns">
  <!--         <select accesskey="a">
              <option value="1">Cash</option>
           </select>-->
            <?php
            echo CHtml::dropDownList('account', 1, CHtml::listData(KasBank::model()->findAll(), 'id', 'nama'), array(
                'accesskey' => 'a',
                'id' => 'account'
            ));
            ?>
        </div>
        <div class="small-3 large-3 columns">
            <span class="postfix"><kbd>Alt</kbd> <kbd>a</kbd></span>
        </div>
    </div>
    <div class="row collapse">
        <?php /* Jenis Pembayaran */ ?>
        <div class="small-3 large-2 columns">
            <span class="prefix"><i class="fa fa-2x fa-circle"></i></span>
        </div>
        <div class="small-6 large-7 columns">
            <?php
            echo CHtml::dropDownList('jenisbayar', 1, CHtml::listData(JenisTransaksi::model()->findAll(), 'id', 'nama'), array(
                'accesskey' => 'd',
                'id' => 'jenisbayar'
            ));
            ?>
        </div>
        <div class="small-3 large-3 columns">
            <span class="postfix"><kbd>Alt</kbd> <kbd>d</kbd></span>
        </div>
    </div>	
    <div class="row collapse">
        <div class="small-3 large-2 columns">
            <span class="prefix huruf">IDR</span>
        </div>
        <div class="small-9 large-10 columns">
            <input type="text" id="uang-dibayar" placeholder="[U]ang Dibayar" accesskey="u"/>
        </div>
    </div>
    <a href="" class="success bigfont tiny button" id="tombol-simpan">Simpan</a>
    <a href="" class="alert bigfont tiny  button" id="tombol-batal">Batal</a>
</div>
<div style="display: none" id="total-belanja-h"><?php echo $model->ambilTotal(); ?></div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    function tampilkanKembalian() {
        //console.log("this:" + $(this).val() + "; total:" + $("#total-belanja-h").text());
        var dataKirim = {
            total: $("#total-belanja-h").text(),
            bayar: $("#uang-dibayar").val()
        };
        $("#kembali").load('<?php echo $this->createUrl('kembalian'); ?>', dataKirim);
    }

    $(function () {
        $(document).on('click', "#tombol-tambah-barang", function () {
            dataUrl = '<?php echo $this->createUrl('tambahbarang', array('id' => $model->id)); ?>';
            dataKirim = {barcode: $("#scan").val()};
            console.log(dataUrl);
            /* Jika tidak ada barang, keluar! */
            if ($("#scan").val() === '') {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.fn.yiiGridView.update('penjualan-detail-grid');
                        updateTotal();
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                            //class_name: 'gritter-center'
                        });
                    }
                    $("#scan").val("");
                    $("#scan").focus();
                }
            });
            return false;
        });
    });

    $("#scan").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah-barang").click();
        }
    });

    $("#scan").autocomplete({
        source: "<?php echo $this->createUrl('caribarang'); ?>",
        minLength: 3,
        search: function (event, ui) {
            $("#scan-icon").html('<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />');
        },
        response: function (event, ui) {
            $("#scan-icon").html('<i class="fa fa-barcode fa-2x"></i>');
        },
        select: function (event, ui) {
            console.log(ui.item ?
                    "Nama: " + ui.item.label + "; Barcode " + ui.item.value :
                    "Nothing selected, input was " + this.value);
            if (ui.item) {
                $("#scan").val(ui.item.value);
            }
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.label + "<br /><small>" + item.value + " [" + item.stok + "][" + item.harga + "]</small></a>")
                .appendTo(ul);
    };

    function updateTotal() {
        var dataurl = "<?php echo Yii::app()->createUrl('penjualan/total', array('id' => $model->id)); ?>";
        $.ajax({
            url: dataurl,
            type: "GET",
            success: function (data) {
                if (data.sukses) {
                    $("#total-belanja-h").text(data.total);
                    $("#total-belanja").text(data.totalF);
                    tampilkanKembalian();
                    console.log(data.totalF);
                }
            }
        });
    }

    $("#uang-dibayar").keyup(function () {
        tampilkanKembalian();
    });

    $("#tombol-simpan").click(function () {
        dataUrl = '<?php echo $this->createUrl('simpan', array('id' => $model->id)); ?>';
        dataKirim = {
            'pos[account]': $("#account").val(),
            'pos[jenistr]': $("#jenisbayar").val(),
            'pos[uang]': $("#uang-dibayar").val()
        };
        console.log(dataUrl);

        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    //cetak();
                    window.open('<?php echo $this->createUrl('out', array('id' => $model->id)); ?>');
                    //window.location.href = "<?php echo $this->createUrl('index'); ?>";
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                }
                $("#scan").val("");
                $("#scan").focus();
            }
        });
        return false;
    });
</script>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
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
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
