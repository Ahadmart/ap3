<?php
/* @var $this ReportController */
/* @var $model ReportPpnForm */
?>
<ul class="button-group left">
    <li>
        <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
        <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
            <?php
            foreach ($printers as $printer) {
            ?>
                <?php
                if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                    /* Jika printer pdf, tambahkan pilihan ukuran kertas */
                ?>
                    <span class="sub-dropdown"><?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></span>
                    <ul>
                        <?php
                        foreach ($kertasPdf as $key => $value) :
                        ?>
                            <li><a target="blank" class="export-pdf" data-kertas="<?= $key ?>" data-printer="<?= $printer['id'] ?>"><?= $value; ?></a></li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                <?php
                } else {
                    /* Untuk nanti
                ?>
                        <li>
                            <a href="<?=
                                        $this->createUrl('printppn', [
                                            'printer' => $printer['id'],
                                        ])
                                        ?>">
                                <?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></a>
                        </li>
                <?php
                */
                }
                ?>
            <?php
            }
            ?>
        </ul>
    </li>
</ul>
<script>
    $(".export-pdf").click(function() {
        formData = {
            'printer': $(this).data('printer'),
            'kertas': $(this).data("kertas"),
            'periode': $("#ReportPpnForm_periode").val(),
            'detailValid': $("#ReportPpnForm_detailPpnPembelianValid").is(':checked') ? 1 : 0,
            'detailPending': $("#ReportPpnForm_detailPpnPembelianPending").is(':checked') ? 1 : 0
        };
        url = "<?= $this->createUrl('printppn') ?>?" + Object.keys(formData).map(key => `${key}=${encodeURIComponent(formData[key])}`).join('&')
        window.open(url)
        return false;
    })
</script>