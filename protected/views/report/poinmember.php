<?php
/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Poin Member',
);

$this->boxHeader['small'] = $judul;
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan ' . $judul;

$this->renderPartial('_form_poin_member', array(
    'model' => $model,
    'listPeriode' => $listPeriode,
    'listSortBy' => $listSortBy
));

if (!is_null($report)):
    ?>

    <?php
    $this->renderPartial('_form_poin_member_cetak', array(
        'model' => $model,
        'kertasPdf' => $kertasPdf
    ));
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">#</th>
                        <th>Nomor</th>
                        <th>Nama</th>
                        <th class="rata-kanan">Jumlah Poin</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Hp</th>
                        <th>Email</th>
                        <th>ID</th>
                        <th>Tgl Lahir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report as $barisReport):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?php echo $i; ?></td>
                            <td><?php echo $barisReport['nomor']; ?></td>
                            <td><a href="<?php echo Yii::app()->controller->createUrl('/profil/view', array('id' => $barisReport['profil_id'])); ?>"><?php echo $barisReport['nama']; ?></td></td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['poin'], 0, ',', '.'); ?></td>
                            <td>
                                <?php echo $barisReport['alamat1']; ?>
                                <?php echo $barisReport['alamat2'] <> '' ? '<br />' . $barisReport['alamat2'] : ''; ?>
                                <?php echo $barisReport['alamat3'] <> '' ? '<br />' . $barisReport['alamat3'] : ''; ?>
                            </td>
                            <td><?php echo $barisReport['telp']; ?></td>
                            <td><?php echo $barisReport['hp']; ?></td>
                            <td><?php echo $barisReport['surel']; ?></td>
                            <td><?php echo $barisReport['identitas']; ?></td>
                            <td><?php echo $barisReport['tanggal_lahir']; ?></td>
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




endif;