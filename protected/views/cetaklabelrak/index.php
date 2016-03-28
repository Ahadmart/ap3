<?php
/* @var $this CetaklabelrakController */

$this->breadcrumbs = array(
    'Cetak Label Rak' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Cetak Label';
$this->boxHeader['normal'] = 'Cetak Label Rak';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="small-12 medium-6 column">
        <div class="panel">
            <h4>Filter Barang</h4>
            <hr />
            <?php $this->renderPartial('_form_input', array('model' => $modelForm)); ?>
            <div id="tabel-profil" style="display: none">
                <?php $this->renderPartial('_profil', array('profil' => $profil)); ?>
            </div>
            <div id="tabel-rak" style="display: none">
                <?php $this->renderPartial('_rak', array('rak' => $rak)); ?>
            </div>
        </div>

    </div>
    <div class="small-12 medium-6 column">
        <div class="panel">
            <h4>Label yang akan dicetak</h4>
            <hr />
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="rata-kanan">Harga Jual</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
    </div>
</div>
<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy'
        });
    });

    $("#tombol-browse-profil").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("#tombol-browse-rak").click(function () {
        $("#tabel-rak").slideToggle(500);
        $("input[name='RakBarang[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    $("body").on("click", "a.pilih.rak", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiRak
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#CetakLabelRakForm_profilId").val(data.id);
    }

    function isiRak(data) {
        console.log(data);
        $("#rak").val(data.nama);
        $("#tabel-rak").slideUp(500);
        $("#CetakLabelRakForm_rakId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>