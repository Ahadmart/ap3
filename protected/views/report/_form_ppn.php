<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'report-ppn-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'htmlOptions'          => ['target' => '_blank'],
    'method'               => 'GET',
]);
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

<div class="row">
    <div class="small-12 columns">
        <?php echo $form->labelEx($model, 'periode'); ?>
        <?php echo $form->textField($model, 'periode', ['class' => 'periode rata-kanan', 'value' => empty($model->tanggal) ? date('Y-m') : $model->tanggal]); ?>
        <?php echo $form->error($model, 'periode', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-6 columns">
        <div class="row">
            <?php echo $form->checkBox($model, 'detailPpnPembelianValid'); ?>
            <?php echo $form->labelEx($model, 'detailPpnPembelianValid'); ?>
            <?php echo $form->error($model, 'detailPpnPembelianValid', ['class' => 'error']); ?>
        </div>
        <div class="row">
            <?php echo $form->checkBox($model, 'detailPpnPembelianPending'); ?>
            <?php echo $form->labelEx($model, 'detailPpnPembelianPending'); ?>
            <?php echo $form->error($model, 'detailPpnPembelianPending', ['class' => 'error']); ?>
        </div>
    </div>

        <div class="small-12 columns">
            <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont button right tombol-submit']); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <table id="rekap" class="tabel-index" style="display:none">
            <thead>
                <tr>
                    <td>Nama</td>
                    <td class="rata-kanan">Total</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <table id="detail-valid" class="tabel-index" style="display:none">
            <caption>Detail PPN Pembelian Valid</caption>
            <thead>
                <tr>
                    <td>Supplier</td>
                    <td>Faktur Pajak</td>
                    <td>Pembelian</td>
                    <td class="rata-kanan">PPN</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <table id="detail-pending" class="tabel-index" style="display:none">
            <thead>
                <tr>
                    <td>Supplier</td>
                    <td>Pembelian</td>
                    <td class="rata-kanan">PPN</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<?php
$this->endWidget();

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<script>
    $(function() {
        $('.periode').fdatepicker({
            format: 'yyyy-mm',
            startView: 'year',
            minView: 'year',
            language: 'id'
        });
    });

    $(".tombol-submit").click(function() {
        var dataKirim = {
            'periode': $("#ReportPpnForm_periode").val(),
            'detailValid': $("#ReportPpnForm_detailPpnPembelianValid").is(':checked') ? 1 : 0,
            'detailPending': $("#ReportPpnForm_detailPpnPembelianPending").is(':checked') ? 1 : 0
        };
        $("#rekap").hide();
        $("#detail-valid").hide();
        $("#detail-pending").hide();
        $.ajax({
            type: "POST",
            url: '<?php echo $this->createUrl('getppn'); ?>',
            data: dataKirim,
            dataType: "json",
            success: function(hasil) {
                if (hasil.sukses) {
                    $("#rekap").show();
                    isiTabelPpn(hasil.data);
                    if (hasil.data.detailPpnPembelianValid.length > 0) {
                        $("#detail-valid").show();
                        isiDetailValid(hasil.data.detailPpnPembelianValid)
                    }
                    if (hasil.data.detailPpnPembelianPending.length > 0) {
                        $("#detail-pending").show();
                        isiDetailPending(hasil.data.detailPpnPembelianPending)
                    }
                }
            }
        });
        return false;
    });

    lang = 'id-ID';
    options = {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }

    function isiTabelPpn(data) {
        var tBody = $("#rekap tbody");
        tBody.html('');
        // console.log("Total Penjualan: " + parseFloat(data.totalPpnPenjualan).toLocaleString(lang, options));
        var totalPpnJual = $('<tr>');
        totalPpnJual.append($('<td>').text('Total PPN Penjualan'));
        totalPpnJual.append($('<td class="rata-kanan">').text(parseFloat(data.totalPpnPenjualan).toLocaleString(lang, options)));
        tBody.append(totalPpnJual);
        var TotalPpnBeliValid = $('<tr>');
        TotalPpnBeliValid.append($('<td>').text('Total PPN Pembelian Valid'));
        TotalPpnBeliValid.append($('<td class="rata-kanan">').text(parseFloat(data.totalPpnPembelianValid).toLocaleString(lang, options)));
        tBody.append(TotalPpnBeliValid);
        var TotalPpnHutang = $('<tr>');
        TotalPpnHutang.append($('<td>').text('PPN Terhutang'));
        TotalPpnHutang.append($('<td class="rata-kanan">').text((parseFloat(data.totalPpnPembelianValid - data.totalPpnPenjualan)).toLocaleString(lang, options)));
        tBody.append(TotalPpnHutang);
        var TotalPpnBeliPending = $('<tr>');
        TotalPpnBeliPending.append($('<td>').text('Total PPN Pembelian Pending'));
        TotalPpnBeliPending.append($('<td class="rata-kanan">').text(parseFloat(data.totalPpnPembelianPending).toLocaleString(lang, options)));
        tBody.append(TotalPpnBeliPending);
    }

    function isiDetailValid(data) {
        var tBody = $("#detail-valid tbody");
        tBody.html("");
        $.each(data, function(i, item) {
            var row = $('<tr>');
            row.append($('<td>').text(item.nama));
            row.append($('<td>').text(item.no_faktur_pajak));
            row.append($('<td>').text(item.nomor));
            row.append($('<td class="rata-kanan">').text(parseFloat(item.jumlah).toLocaleString(lang, options)));
            tBody.append(row);
        })
    }
    function isiDetailPending(data) {
        var tBody = $("#detail-pending tbody");
        tBody.html("");
        $.each(data, function(i, item) {
            var row = $('<tr>');
            row.append($('<td>').text(item.nama));
            row.append($('<td>').text(item.nomor));
            row.append($('<td class="rata-kanan">').text(parseFloat(item.jumlah).toLocaleString(lang, options)));
            tBody.append(row);
        })
    }
</script>