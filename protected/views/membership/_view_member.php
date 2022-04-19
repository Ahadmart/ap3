<h4><small>Profil</small> Member</h4>
<hr />
<?php
$this->widget('BDetailView', [
    'data'       => $model,
    'attributes' => [
        'nomor',
        'nomorTelp',
        'namaLengkap',
        'jenisKelamin',
        'tanggalLahir',
        'pekerjaanNama',
        'alamat',
        'keterangan'
    ],
]);
