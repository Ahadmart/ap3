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
                                foreach ($kertasPdf as $key => $value) :
                                ?>
                                    <li><a target="blank" href="<?=
                                                                $this->createUrl('printtoprank', [
                                                                    'printId'    => $printer['id'],
                                                                    'kertas'     => $key,
                                                                    'profilId'   => $model->profilId,
                                                                    'dari'       => $model->dari,
                                                                    'sampai'     => $model->sampai,
                                                                    'kategoriId' => $model->kategoriId,
                                                                    'rakId'      => $model->rakId,
                                                                    'limit'      => $model->limit,
                                                                    'sortBy'     => $model->sortBy,
                                                                    'strukLv1'   => $model->strukLv1,
                                                                    'strukLv2'   => $model->strukLv2,
                                                                    'strukLv3'   => $model->strukLv3,
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
                                            $this->createUrl('printtoprank', [
                                                'printId'    => $printer['id'],
                                                'profilId'   => $model->profilId,
                                                'dari'       => $model->dari,
                                                'sampai'     => $model->sampai,
                                                'kategoriId' => $model->kategoriId,
                                                'rakId'      => $model->rakId,
                                                'limit'      => $model->limit,
                                                'sortBy'     => $model->sortBy,
                                                'strukLv1'   => $model->strukLv1,
                                                'strukLv2'   => $model->strukLv2,
                                                'strukLv3'   => $model->strukLv3,
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