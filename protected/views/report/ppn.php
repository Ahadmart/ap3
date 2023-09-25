<?php

/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Pembelian',
];

$this->boxHeader['small']  = 'Laporan PPN';
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan PPN';

$this->renderPartial('_form_ppn', [
    'model' => $model,
]);
?>
<div class="row" style="display:none" id="tombol-cetak">
    <div class="small-12 columns">
        <?php
        $this->renderPartial('_form_ppn_cetak', [
            'model'     => $model,
            'printers'  => $printers,
            'kertasPdf' => $kertasPdf,
        ]);
        ?>
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
            <caption>Detail PPN Pembelian Pending</caption>
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
<script>
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
        TotalPpnHutang.append($('<td class="rata-kanan">').text((parseFloat(data.totalPpnPenjualan - data.totalPpnPembelianValid)).toLocaleString(lang, options)));
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