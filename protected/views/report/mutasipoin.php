<?php
/* @var $this ReportController */

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Mutasi Poin Member',
];

$this->boxHeader['small']  = 'Mutasi Poin';
$this->boxHeader['normal'] = '<i class="fa fa-star fa-lg"></i> Laporan Mutasi Poin';

$this->renderPartial('_form_mutasipoin', ['model' => $model]);
/*
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', ['profil' => $profil]); ?>
        </div>
    </div>
</div>
<?php
*/
if (isset($report)) {
    echo '<pre>';
    print_r($report);
    echo '</pre>';
    // $this->renderPartial('_form_toprank_cetak', [
    //     'model'     => $model,
    //     'printers'  => $printers,
    //     'kertasPdf' => $kertasPdf
    // ]);
    ?>
    <!-- <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan">Omset</th>
                        <th class="rata-kanan">Profit</th>
                        <th class="rata-kanan">Avg / Day</th>
                        <th class="rata-kanan">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    /*
                    $i = 1;
                    foreach ($report as $baris):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?php echo $i; ?></td>
                            <td><?php echo $baris['barcode']; ?></td>
                            <td><?php echo $baris['nama']; ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['totalqty'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['total'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['margin'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['avgday'], 2, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($baris['stok'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    */
                    ?>
                </tbody>
            </table>
        </div>
    </div> -->
    <?php
}
?>

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
        $("#ReportTopRankForm_profilId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>
