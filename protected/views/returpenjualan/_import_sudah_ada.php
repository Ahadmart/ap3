<h6>Data sudah pernah diimport</h6>
<table style="width: 100%">
    <thead>
        <tr>
            <th>Retur Penjualan</th><th>Tgl</th><th>Ref</th><th class="rata-kanan">Total</th>
        </tr>
    </thead>
    <?php
    foreach ($pembelian as $row) {
        $tanggal = date_format(date_create_from_format('Y-m-d H:i:s', $row['tanggal']), 'd-m-Y H:i:s');
        ?>
        <tr>
            <td><?php echo CHtml::link($row['nomor'], $this->createUrl('view', ['id' => $row['id']])); ?></td>
            <td>
                <?php
                if (isset($row['nomor'])) {
                    echo $tanggal;
                } else {
                    echo CHtml::link($tanggal, $this->createUrl('ubah', ['id' => $row['id'], 'pilihb' => FALSE]));
                }
                ?>
            </td>
            <td><?= $row['referensi']; ?></td>
            <td class="rata-kanan"><?= number_format($row['total'], 0, ',', '.'); ?></td>
        </tr>
        <?php
    }
    ?>
</table>
