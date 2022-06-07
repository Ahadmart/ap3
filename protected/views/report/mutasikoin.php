<?php
/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Mutasi Koin Member',
];

$this->boxHeader['small']  = 'Mutasi Koin';
$this->boxHeader['normal'] = '<i class="fa fa-star fa-lg"></i> Laporan Mutasi Koin';

$this->renderPartial('_form_mutasikoin', ['model' => $model]);

?>
<div class="row">
    <div class="small-12 columns">
        <table id="tabel-mutasikoin" class="tabel-index" style="width:100%">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>Penjualan</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script>
    function formatDate(timestamp) {
        function pad(num) {
            return num.toString().padStart(2, '0');
        }
        var d = new Date(timestamp)
        return [
                pad(d.getDate()),
                pad(d.getMonth() + 1),
                d.getFullYear()
            ].join('-') +
            ' ' + [
                pad(d.getHours()),
                pad(d.getMinutes()),
                pad(d.getSeconds()),
            ].join(':')
    }

    function isiTabel(data) {
        var tableBody = $("#tabel-mutasikoin>tbody");
        tableBody.html("");
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td></td>' +
            '<td>Saldo Awal</td>' +
            '<td>' + data.saldoAwal + '</td>' +
            '<td></td>';
        tableBody.append(tr);
        data.mutasi.forEach(function(object) {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td>' + formatDate(object.tanggal) + '</td>' +
                '<td>' + object.penjualan + '</td>' +
                '<td>' + object.jumlah + '</td>' +
                '<td>' + object.keterangan + '</td>';
            tableBody.append(tr);
        });
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td></td>' +
            '<td>Saldo Akhir</td>' +
            '<td>' + data.saldoAkhir + '</td>' +
            '<td></td>';
        tableBody.append(tr);
    }
</script>