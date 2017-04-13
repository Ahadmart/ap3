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
            <?php $this->renderPartial('_form_input', ['model' => $modelForm, 'scanBarcode' => $scanBarcode]); ?>
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
            <?php
            $this->renderPartial('_form_layout', array(
                'model' => $layoutForm
            ));
            ?>

            <div class="row collapse" style="overflow: auto">
                <div class="small-12 columns">
            <?php $this->renderPartial('_barang', array('model' => $labelCetak)); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
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