<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js',
        CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js',
        CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Penjualan Sales Order',
];

$this->boxHeader['small']  = 'Penjualan SO';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Penjualan dari Sales Order';

$this->renderPartial('_form_penjualan_salesorder', ['model' => $model]);
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', ['profil' => $profil]); ?>
        </div>
        <div id="tabel-user" style="display: none">
            <?php $this->renderPartial('_user', ['user' => $user]); ?>
        </div>
    </div>
</div>
<?php
if (isset($report['rekap']) && $report['rekap']) {
    ?>
    <div class="row">
        <div class="small-6 columns">
            <?php
            $this->renderPartial('_form_penjualan_salesorder_cetak',
                    [
                'model'    => $model,
                'printers' => $printers,
                    //'kertasPdf' => $kertasPdf
            ]);
            ?>
        </div>
        <div class="small-6 columns rata-kanan">
            <h6>Total : <?php echo number_format($report['rekap']['total'], 0, ',', '.'); ?></h6>
            <h6>Margin : <?php echo number_format($report['rekap']['margin'], 0, ',', '.'); ?></h6>
            <?php if ($report['rekap']['total'] != 0) {
                ?>
                <h6>Profit Margin: <?php
                    echo number_format($report['rekap']['margin'] / $report['rekap']['total'] * 100, 2, ',', '.');
                    ?>%</h6>
                <?php
            }
            ?>
            <?php
            if (!empty($report['detail'])):
                ?>
                <h6><?= count($report['detail']) ?> Transaksi</h6>
                <?php
            endif;
            ?>
        </div>
    </div>
    <?php
}
if (!empty($report['detail'])):
    ?>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">#</th>
                        <th>Tanggal</th>
                        <th># Penjualan</th>
                        <th># Sales Order</th>
                        <th>Profil</th>
                        <th class="rata-kanan">Total</th>
                        <th class="rata-kanan">Margin</th>
                        <th class="rata-kanan">Profit Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report['detail'] as $barisReport):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><?php echo $barisReport['tanggal']; ?></td>
                            <td><a href="<?php
                                echo Yii::app()->createUrl('penjualan/view', ['id' => $barisReport['penjualan_id']]);
                                ?>"><?php echo $barisReport['penjualan_nomor']; ?></a></td>
                            <td><a href="<?php
                                echo Yii::app()->createUrl('salesorder/view', ['id' => $barisReport['so_id']]);
                                ?>"><?php echo $barisReport['so_nomor']; ?></a></td>
                            <td><?= $barisReport['nama']; ?> </td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['total'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['margin'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php
                                echo $barisReport['total'] == 0 ? '' : number_format($barisReport['margin'] / $barisReport['total'] * 100,
                                                2, ',', '.') . '%';
                                ?></td>
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
?>
<script>
    $(function () {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });

    $("#tombol-browse-profil").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("#tombol-browse-user").click(function () {
        $("#tabel-user").slideToggle(500);
        $("input[name='User[nama_lengkap]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    $("body").on("click", "a.pilih.user", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiUser
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportPenjualanSalesOrderForm_profilId").val(data.id);
    }

    function isiUser(data) {
        console.log(data);
        $("#user").val(data.namaLengkap + ' (' + data.nama + ')');
        $("#tabel-user").slideUp(500);
        $("#ReportPenjualanSalesOrderForm_userId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>