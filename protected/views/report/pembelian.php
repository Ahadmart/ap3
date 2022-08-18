<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = [
    'Laporan' => ['index'],
    'Pembelian',
];

$this->boxHeader['small']  = 'Pembelian';
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan Pembelian';

$this->renderPartial('_form_pembelian', ['model' => $model]);
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', ['profil' => $profil]); ?>
        </div>
    </div>
</div>
<?php
if (!empty($report['detail'])) :
?>
    <div class="row">
        <div class="small-6 columns">
            <?php
            $this->renderPartial('_form_pembelian_cetak', [
                'model'    => $model,
                'printers' => $printers,
                //'kertasPdf' => $kertasPdf
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <table class="tabel-index responsive">
                <thead>
                    <tr>
                        <th class="rata-kanan">No</th>
                        <th>Tanggal</th>
                        <th>Referensi</th>
                        <th>Nomor</th>
                        <th class="rata-kanan">Total</th>
                        <th>Profil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($report['detail'] as $barisReport) :
                    ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?>
                            </td>
                            <td><?php echo $barisReport['tanggal']; ?>
                            </td>
                            <td><?= $barisReport['referensi']; ?>
                            </td>
                            <td><a href="<?php echo Yii::app()->createUrl('pembelian/view', ['id' => $barisReport['pembelian_id']]); ?>"><?php echo $barisReport['nomor']; ?></a></td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['jumlah'], 0, ',', '.'); ?>
                            </td>
                            <td><?= $barisReport['profil']; ?>
                            </td>
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
    $(function() {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });

    $("#tombol-browse").click(function() {
        $("#tabel-profil").slideToggle(500);
        $("input[name='Profil[nama]']").focus();
    });

    $("body").on("click", "a.pilih.profil", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: isiProfil
        });
        return false;
    });

    function isiProfil(data) {
        console.log(data);
        $("#profil").val(data.nama);
        $("#tabel-profil").slideUp(500);
        $("#ReportPembelianForm_profilId").val(data.id);
        $("#ReportPembelianForm_dari").focus();
    }

    $("#tombol-hapusprofil").click(function() {
        // console.log('tombol-hapusprofil fired')
        hapusProfil();
    })

    function hapusProfil() {
        $("#profil").val('');
        $("#ReportPembelianForm_profilId").val('');
        // console.log('hapusProfil fired')
    }

    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>