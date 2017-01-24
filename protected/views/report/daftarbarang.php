<?php
/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => array('index'),
    'Daftar Barang',
];

$this->boxHeader['small'] = 'Daftar Barang';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Daftar Barang';

$this->renderPartial('_form_daftarbarang', ['model' => $model]);
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', array('profil' => $profil)); ?>
        </div>
    </div>
</div>
<script>

    $("#tombol-browse-profil").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportDaftarBarangForm_profilId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>
<?php
$this->renderPartial('_form_daftarbarang_cetak', [
    'model' => $model,
    'printers' => $printers,
        //'kertasPdf' => $kertasPdf
]);
if (isset($report)) {

    $this->renderPartial('_form_daftarbarang_cetak', [
        'model' => $model,
        'printers' => $printers,
            //'kertasPdf' => $kertasPdf
    ]);
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Kategori</th>
                        <th class="rata-kanan">HPP</th>
                        <th class="rata-kanan">Harga Jual</th>
                        <th class="rata-kanan">RRP</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report as $baris):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?php echo $i; ?></td>
                            <td><?php echo $baris['barcode']; ?></td>
                            <td><?php echo $baris['nama']; ?></td>
                            <td><?php echo $baris['nama_kategori']; ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['hpp'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['harga_jual'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['rrp'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
