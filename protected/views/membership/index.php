<?php

/* @var $this LaporanharianController */
/* @var $model LaporanHarian */

$this->breadcrumbs = [
    'Membership' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Membership';
$this->boxHeader['normal'] = 'Membership';

$this->renderPartial('_form_search');
?>
<div class="row">
    <div class="small-12 columns">
        <table id="list-profil" class="tabel-index" style="width:100%">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Nomor</th>
                    <th onclick="sortTable(1)">No Telp</th>
                    <th onclick="sortTable(2)">Nama Lengkap</th>
                    <!-- <th onclick="sortTable(3)">Tanggal Lahir</th> -->
                    <th onclick="sortTable(3)">Umur</th>
                    <th onclick="sortTable(4)">Pekerjaan</th>
                    <th onclick="sortTable(5)">Alamat</th>
                    <th onclick="sortTable(6)">Keterangan</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script>
    function isiTabel(data) {
        var tableBody = $("#list-profil>tbody");
        tableBody.html("");
        data.forEach(function(object) {
            var tr = document.createElement('tr');
            tr.innerHTML = '<td><a href="<?= $this->createUrl('/membership') ?>/' + object.nomor + '">' + object.nomor + '</a></td>' +
                '<td>' + object.kode_negara + object.nomor_telp + '</td>' +
                '<td>' + object.nama_lengkap + '</td>' +
                '<td>' + object.umur + '</td>' +
                '<td>' + object.pekerjaan + '</td>' +
                '<td>' + object.alamat + '</td>' +
                '<td>' + object.keterangan + '</td>';
            tableBody.append(tr);
        });
    }
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => '',
        'items'    => [
            ['label' => '<i class="fa fa-sliders"></i> <span class="ak">C</span>onfig', 'url' => $this->createUrl('/membershipconfig'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'c'
            ]],
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">R</span>egistrasi', 'url' => $this->createUrl('registrasi'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'r'
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => '',
        'items'    => [
            ['label' => '<i class="fa fa-sliders"></i>', 'url' => $this->createUrl('/membershipconfig'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'c'
            ]],
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('registrasi'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'r'
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
