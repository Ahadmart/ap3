<h4><small>Profil</small> Member</h4>
<hr />
<?php
$this->widget('BDetailView', [
    'data'       => $model,
    'attributes' => [
        'nomor',
        // 'nomorTelp',
        [
            'label' => 'Nomor Telp',
            'value' => $model->kodeNegara . $model->nomorTelp
        ],
        'namaLengkap',
        'jenisKelamin',
        'tanggalLahir',
        'pekerjaanNama',
        'alamat',
        'keterangan'
    ],
]);
