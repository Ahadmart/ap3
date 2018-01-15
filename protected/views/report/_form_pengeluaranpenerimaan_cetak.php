<?php
    /* @var $this ReportController */
    /* @var $model ReportPengeluaranPenerimaanForm */
?>
    <ul class="button-group left">
        <li>
            <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown">
                <i class="fa fa-print fa-fw"></i>
                <span class="ak">C</span>etak</a>
            <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                <?php
                    foreach ($printers as $printer) {
                    ?>
                    <li>
                        <a href="<?=$this->createUrl('printpengeluaranpenerimaan', [
        'printId'   => $printer['id'],
        'dari'      => $model->dari,
        'sampai'    => $model->sampai,
        'itemKeuId' => $model->itemKeuId,
        'profilId'  => $model->profilId,
    ])
    ?>">
                            <?=$printer['nama'];?>
                                <small>
                                    <?=$printer['keterangan'];?>
                                </small>
                        </a>
                    </li>
                    <?php
                        }
                    ?>
            </ul>
        </li>
    </ul>