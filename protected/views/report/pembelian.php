<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Pembelian',
];

$this->boxHeader['small']  = 'Pembelian';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Pembelian';

$this->renderPartial('_form_pembelian', [
    'model'          => $model,
    'optionPrinters' => $optionPrinters,
    'printHandle'    => $printHandle,
]);
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', ['profil' => $profil]); ?>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });

    $("#tombol-browse").click(function() {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportPembelianForm_profilId").val(data.id);
        $("#ReportPembelianForm_dari").focus();
    }

    $("#tombol-hapusprofil").click(function() {
        // console.log('tombol-hapusprofil fired')
        hapusProfil();
    })

    function hapusProfil() {
        $("#profil").val('');
        $("#ReportPembelianForm_profilId").val('');
        // console.log('hapusProfil fired')
    }

    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>