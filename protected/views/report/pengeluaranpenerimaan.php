<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Pengeluaran Penerimaan',
);

$this->boxHeader['small'] = 'Pengeluaran Penerimaan';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Pengeluaran Penerimaan';

$this->renderPartial('_form_pengeluaranpenerimaan', array('model' => $model));
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', array('profil' => $profil)); ?>
        </div>
        <div id="tabel-itemkeu" style="display: none">
            <?php $this->renderPartial('_item_keu', array('itemKeuangan' => $itemKeuangan)); ?>
        </div>
    </div>
</div>
<?php
if (isset($report['rekap']) && $report['rekap']) {
    ?>
    <div class="row">
        <div class="small-12 columns rata-kanan">
            <h6>Total : <?php echo number_format($report['rekap']['total'], 0, ',', '.'); ?></h6>
            <h6>Margin : <?php echo number_format($report['rekap']['margin'], 0, ',', '.'); ?></h6>
            <?php if ($report['rekap']['total'] != 0) {
                ?>
                <h6>Profit Margin: <?php echo number_format($report['rekap']['margin'] / $report['rekap']['total'] * 100, 2, ',', '.'); ?>%</h6>
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
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Profil</th>
                        <th>Nota Ket</th>
                        <th>Item Ket</th>
                        <th class="rata-kanan">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report['detail'] as $baris):
                        ?>
                        <tr>
                            <td><?= $baris['tanggal']; ?></td>
                            <td><?= $baris['nomor']; ?></td>
                            <td><?= $baris['profil']; ?></td>
                            <td><?= $baris['nota_ket']; ?></td>
                            <td><?= "{$baris['keterangan']}"; ?></td>
                            <td class="rata-kanan"><?= number_format($baris['jumlah'], 0, ',', '.'); ?></td>
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
            format: 'dd-mm-yyyy'
        });
    });

    $("#tombol-browse-profil").click(function () {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("#tombol-browse-itemkeu").click(function () {
        $("#tabel-itemkeu").slideToggle(500);
        $("input[name='ItemKeuangan[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    $("body").on("click", "a.pilih.itemkeu", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiItemKeu
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportPengeluaranPenerimaanForm_profilId").val(data.id);
    }

    function isiItemKeu(data) {
        console.log(data);
        $("#itemKeu").val('(' + data.parent + ') '+data.nama);
        $("#tabel-itemkeu").slideUp(500);
        $("#ReportPengeluaranPenerimaanForm_itemKeuId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>