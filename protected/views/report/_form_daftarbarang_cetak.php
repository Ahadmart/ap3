<?php
/* @var $this ReportController */
/* @var $model ReporDaftarBarangForm */
?>
<div class="row">
    <div class="small-12 columns">

        <ul class="button-group right">
            <li>
                <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
                <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printers as $printer) {
                        ?>
                        <?php
                        if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                            /* Jika printer pdf, do nothing.. belum ada */
                        } else {
                            ?>
                            <li>
                                <a class="tombol-cetak" href="<?=
                                   $this->createUrl('printdaftarbarang', [
                                       'printId' => $printer['id']
                                   ])
                                   ?>">
                                    <?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>  
    </div>
</div>
<script>
    $(".tombol-cetak").click(function () {
        var dataKirim = {
            'profilId': $("#ReportDaftarBarangForm_profilId").val(),
            'hanyaDefault': $("#ReportDaftarBarangForm_hanyaDefault").is(':checked') ? 1 : 0,
            'filterNama': $("#ReportDaftarBarangForm_filterNama").val(),
            'sortBy0': $("#ReportDaftarBarangForm_sortBy0").val(),
            'sortBy1': $("#ReportDaftarBarangForm_sortBy1").val()
        };
        var dataUrl = $(this).attr('href');
        window.open(dataUrl + '&' + $.param(dataKirim), '_blank');
        return false;
    });
</script>