<?php
/* @var $this ReportController */
/* @var $model ReporHutangPiutangForm */
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
                            /* Jika printer pdf, tambahkan pilihan ukuran kertas */
                            ?>
                            <span class="sub-dropdown"><?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></span>
                            <ul>
                                <?php
                                foreach ($kertasPdf as $key => $value):
                                    ?>
                                    <li><a target="blank" href="<?=
                                        $this->createUrl('printhutangpiutang', [
                                            'printId' => $printer['id'],
                                            'kertas' => $key,
                                            'profilId' => $model->profilId,
                                            'showDetail' => $model->showDetail,
                                            'pilihCetak' => $model->pilihCetak
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
                                $this->createUrl('printhutangpiutang', [
                                    'printId' => $printer['id'],
                                    'profilId' => $model->profilId,
                                    'showDetail' => $model->showDetail,
                                    'pilihCetak' => $model->pilihCetak
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