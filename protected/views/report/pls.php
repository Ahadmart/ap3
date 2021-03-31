<?php
/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'PLS',
];

$this->boxHeader['small']  = 'PLS';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Potensi Lost Sales';

$this->renderPartial('_form_pls', ['model' => $model]);
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', ['profil' => $profil]); ?>
        </div>
        <!--        <div id="tabel-user" style="display: none">
        <?php //$this->renderPartial('_user', array('user' => $user));
        ?>
                </div>-->
    </div>
</div>
<script>
    $("#tombol-browse-profil").click(function() {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function() {
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
        $("#ReportPlsForm_profilId").val(data.id);
    }
</script>
<?php
if (isset($report)) {
    $this->renderPartial('_form_pls_cetak', [
        'model'     => $model,
        'printers'  => $printers,
        'kertasPdf' => $kertasPdf,
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
                        <th class="rata-kanan">Penjualan</th>
                        <th class="rata-kanan">Penjualan/Hari (ADS)</th>
                        <th class="rata-kanan">Stok</th>
                        <th class="rata-kanan">Estimasi Sisa Hari</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report as $strukturLv3) :
                        foreach ($strukturLv3 as $baris) :
                    ?>
                            <tr>
                                <td class="rata-kanan">
                                    <?php echo $i; ?>
                                </td>
                                <td>
                                    <?php echo $baris['barcode']; ?>
                                </td>
                                <td>
                                    <?php echo $baris['nama']; ?>
                                </td>
                                <td class="rata-kanan">
                                    <?php echo number_format($baris['qty'], 0, ',', '.'); ?>
                                </td>
                                <td class="rata-kanan">
                                    <?php echo number_format($baris['ads'], 4, ',', '.'); ?>
                                </td>
                                <td class="rata-kanan">
                                    <?php echo number_format($baris['stok'], 0, ',', '.'); ?>
                                </td>
                                <td class="rata-kanan">
                                    <?php echo number_format($baris['sisa_hari'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                    <?php
                            $i++;
                        endforeach;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}
