<option>Pilih satu..</option>
<?php
foreach ($listBukanSupplier as $supplier):
    ?>
    <option value="<?php echo $supplier['id']; ?>">
        <?php
        echo $supplier['nama'];
        echo (isset($supplier['alamat1']) and $supplier['alamat1'] != '') ? ' | ' . "{$supplier['alamat1']} {$supplier['alamat2']} {$supplier['alamat3']}" : '';
        ?>
    </option>
    <?php
endforeach;
//print_r($listBukanSupplier);
//eof