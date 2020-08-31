<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Stock Opname',
];

$this->boxHeader['small']  = 'Stock Opname';
$this->boxHeader['normal'] = '<i class="fa fa-check-square-o fa-lg"></i> Laporan Stock Opname';

$this->renderPartial('_form_stock_opname', ['model' => $model]);
?>
<div class="row">
    <div class="small-12 columns">
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
//            $this->renderPartial('_form_pergerakan_barang_cetak', array(
//                'model' => $model,
//                'printers' => $printers,
//                    //'kertasPdf' => $kertasPdf
//            ));
            ?>
        </div>
        <div class="small-6 columns rata-kanan">
            <h4><small>Total</small> <?php echo number_format($report['rekap']['total'], 0, ',', '.'); ?></h4>
            <h4><small>Total Qty</small> <?php echo number_format($report['rekap']['total_qty'], 0, ',', '.'); ?></h4>
            <h4><small>Jenis Barang</small> <?php echo number_format($report['rekap']['jenis_barang'], 0, ',', '.'); ?></h4>
            <pre>
                <?php // print_r($report['rekap']) ?>
            </pre>

        </div>
    </div>
    <?php
}
if (!empty($report['detail'])):
    ?>
    <div class="row">
        <div class="small-6 columns rata-kiri">
            <?php
            $this->renderPartial('_form_stockopname_cetak', [
                'model'     => $model,
                'printers'  => $printers,
                'kertasPdf' => $kertasPdf
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th rowspan="2">SO#</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">Barcode</th>
                        <th rowspan="2">Nama</th>                        
                        <?php
                        if ($nilaiDenganHargaJual) {
                            ?>
                            <th colspan="3" class="rata-tengah">Tercatat</th>  
                            <th colspan="3" class="rata-tengah">Fisik</th>   
                            <?php
                        } else {
                            ?>
                            <th colspan="2" class="rata-tengah">Tercatat</th>  
                            <th colspan="2" class="rata-tengah">Fisik</th>   
                            <?php
                        }
                        ?>                              
                        <th colspan="2" class="rata-tengah">Selisih</th>
                        <!--<th rowspan="2">User</th>-->  
                    </tr>
                    <tr>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan">Nilai</th>
                        <?php if ($nilaiDenganHargaJual) { ?><th>Harga Jual</th><?php } ?>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan">Nilai</th>
                        <?php if ($nilaiDenganHargaJual) { ?><th class="rata-kanan">Harga Jual</th><?php } ?>
                        <th class="rata-kanan">Qty</th>
                        <th class="rata-kanan"><?php echo $nilaiDenganHargaJual ? 'Harga Jual' : 'Nilai' ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i                   = 1;
                    $totalQtySelisih     = 0;
                    $totalNominalSelisih = 0;
                    foreach ($report['detail'] as $barisReport):
                        $nominalTercatat   = $barisReport['qty_tercatat'] * $barisReport['harga_beli'];
                        $nominalSebenarnya = $barisReport['qty_sebenarnya'] * $barisReport['harga_beli'];
                        $qtySelisih        = $barisReport['qty_sebenarnya'] - $barisReport['qty_tercatat'];
                        $nominalSelisih    = $qtySelisih * $barisReport['harga_beli'];
                        if ($nilaiDenganHargaJual) {
                            $nominalTercatatHJ   = $barisReport['qty_tercatat'] * $barisReport['harga_jual'];
                            $nominalSebenarnyaHJ = $barisReport['qty_sebenarnya'] * $barisReport['harga_jual'];
                            $nominalSelisih      = $qtySelisih * $barisReport['harga_jual'];
                        }
                        $totalQtySelisih     += $qtySelisih;
                        $totalNominalSelisih += $nominalSelisih;
                        ?>
                        <tr>
                            <td><?= $barisReport['nomor'] ?></td>
                            <td><?= $barisReport['tanggal'] ?></td>
                            <td><?= $barisReport['barcode']; ?></td>
                            <td><?= $barisReport['nama']; ?> </td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['qty_tercatat'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($nominalTercatat, 0, ',', '.'); ?></td>
                            <?php if ($nilaiDenganHargaJual) { ?><td class="rata-kanan"><?php echo number_format($nominalTercatatHJ, 0, ',', '.'); ?></td><?php } ?>
                            <td class="rata-kanan"><?php echo number_format($barisReport['qty_sebenarnya'], 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($nominalSebenarnya, 0, ',', '.'); ?></td>
                            <?php if ($nilaiDenganHargaJual) { ?><td class="rata-kanan"><?php echo number_format($nominalSebenarnyaHJ, 0, ',', '.'); ?></td><?php } ?>
                            <td class="rata-kanan"><?php echo number_format($qtySelisih, 0, ',', '.'); ?></td>
                            <td class="rata-kanan"><?php echo number_format($nominalSelisih, 0, ',', '.'); ?></td>
                            <!--<td><?= $barisReport['nama_user']; ?> </td>-->
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                    <tr>
                        <td colspan="<?php echo $nilaiDenganHargaJual ? 10 : 8 ?>" class="rata-tengah">T O T A L</td>
                        <td class="rata-kanan"><?php echo number_format($totalQtySelisih, 0, ',', '.'); ?></td>
                        <td class="rata-kanan"><?php echo number_format($totalNominalSelisih, 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
endif;
?>
<script>

    $("#tombol-browse-user").click(function () {
        $("#tabel-user").slideToggle(500);
        $("input[name='User[nama_lengkap]']").focus();
    });

    $("body").on("click", "a.pilih.user", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiUser
        });
        return false;
    });

    function isiUser(data) {
        console.log(data);
        $("#user").val(data.namaLengkap + ' (' + data.nama + ')');
        $("#tabel-user").slideUp(500);
        $("#ReportStockOpnameForm_userId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>