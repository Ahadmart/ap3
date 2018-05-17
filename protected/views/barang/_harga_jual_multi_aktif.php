<table class="tabel-index">
    <tr>
        <th>Satuan</th>
        <th class="rata-kanan">Isi</th>
        <th class="rata-kanan">Harga @</th>
    </tr>
    <?php
        foreach ($hjMultiList as $harga) {
            ?>
    <tr>
        <td><?= $harga['nama_satuan'] ?></td>
        <td class="rata-kanan"><?= $harga['qty'] ?></td>
        <td class="rata-kanan"><?= number_format($harga['harga'], 0, ',', '.') ?></td>
    </tr>
            <?php
        }
        ?>
</table>
