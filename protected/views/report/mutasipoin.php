<?php
/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Mutasi Poin Member',
];

$this->boxHeader['small']  = 'Mutasi Poin';
$this->boxHeader['normal'] = '<i class="fa fa-star fa-lg"></i> Laporan Mutasi Poin';

$this->renderPartial('_form_mutasipoin', ['model' => $model]);

?>
<div class="row">
    <div class="small-12 columns">
        <table id="tabel-mutasipoin" class="tabel-index" style="width:100%">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>Penjualan</th>
                    <th class="rata-kanan">Jumlah</th>
                    <th class="rata-kanan">Saldo</th>
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
        var tableBody = $("#tabel-mutasipoin>tbody");
        tableBody.html("");
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td></td>' +
            '<td>Saldo Awal</td>' +
            '<td class="rata-kanan">' + data.saldoAwal + '</td>' +
            '<td></td>' +
            '<td></td>';
        tableBody.append(tr);
        var subTotal = parseInt(data.saldoAwal);
        data.mutasi.forEach(function(object) {
            subTotal += parseInt(object.jumlah);
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td>' + formatDate(object.tanggal) + '</td>' +
                '<td>' + object.penjualan + '</td>' +
                '<td class="rata-kanan">' + object.jumlah + '</td>' +
                '<td class="rata-kanan">' + subTotal + '</td>' +
                '<td>' + object.keterangan + '</td>';
            tableBody.append(tr);
        });
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td></td>' +
            '<td>Saldo Akhir</td>' +
            '<td class="rata-kanan">' + data.saldoAkhir + '</td>' +
            '<td></td>' +
            '<td></td>';
        tableBody.append(tr);
    }
</script>