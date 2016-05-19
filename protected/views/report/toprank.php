<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Top Rank',
);

$this->boxHeader['small'] = 'Top Rank';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Top Rank';

$this->renderPartial('_form_toprank', array('model' => $model));

if (!empty($report['detail'])):
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th class="rata-kanan">Total</th>
                        <th class="rata-kanan">Margin</th>
                        <th class="rata-kanan">Profit Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($report['detail'] as $barisReport):
                        ?>
                        <tr>
                            <td><?php echo $barisReport['tanggal']; ?></td>
                            <td><a href="<?php echo Yii::app()->createUrl('penjualan/view', array('id' => $barisReport['id'])); ?>"><?php echo $barisReport['nomor']; ?></a></td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['total'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['margin'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['margin'] / $barisReport['total'] * 100, 2, ',', '.') . '%'; ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
endif;
?>
<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy'
        });
    });
</script>