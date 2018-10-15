<?php
/* @var $this ReportController */
/* @var $model ReportPenjualanSalesOrderForm */
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
                        foreach ($kertasPdf as $key => $value):
                            ?>
                            <li><a target="blank" href="<?=
                                $this->createUrl('printpenjualanso', [
                                    'printId' => $printer['id'],
                                    'kertas' => $key
                                ])
                                ?>"><?= $value; ?></a></li>
                                <?php
                            endforeach;
                            ?>
                    </ul>
                    <?php
                } else {
                    ?>
                    <li>
                        <a href="<?=
                        $this->createUrl('printpenjualanso', [
                            'printId' => $printer['id'],
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