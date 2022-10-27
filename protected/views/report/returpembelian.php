<?php
/* @var $this ReportController */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Retur Pembelian',
);

$this->boxHeader['small'] = 'Retur Beli';
$this->boxHeader['normal'] = '<i class="fa fa-database fa-lg"></i> Laporan Retur Pembelian';

$this->renderPartial('_form_retur_pembelian', array('model' => $model));
?>
<div class="row">
    <div class="small-12 columns">
        <div id="tabel-profil" style="display: none">
            <?php $this->renderPartial('_profil', array('profil' => $profil)); ?>
        </div>
        <div id="tabel-user" style="display: none">
            <?php $this->renderPartial('_user', array('user' => $user)); ?>
        </div>
    </div>
</div>
<?php
if (isset($report['rekap']) && $report['rekap']) {
    ?>
    <div class="row">
        <div class="small-12 columns rata-kanan">
            <h6>Total : <?php echo number_format($report['rekap']['total'], 0, ',', '.'); ?></h6>
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
                        <th class="rata-kanan">No</th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Status</th>
                        <th>Profil</th>
                        <th class="rata-kanan">Total</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $statusRetur = ReturPembelian::listStatus();
                    $i = 1;
                    foreach ($report['detail'] as $barisReport):
                        ?>
                        <tr>
                            <td class="rata-kanan"><?= $i ?></td>
                            <td><?php echo $barisReport['tanggal']; ?></td>
                            <td><a href="<?php echo Yii::app()->createUrl('returpembelian/view', array('id' => $barisReport['id'])); ?>"><?php echo $barisReport['nomor']; ?></a></td>
                            <td><?= $statusRetur[$barisReport['status']] ?></td>
                            <td><?= $barisReport['nama_profil']; ?> </td>
                            <td class="rata-kanan"><?php echo number_format($barisReport['total'], 0, ',', '.'); ?></td>
                            <td><?= $barisReport['nama_user']; ?> </td>
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
        $("#ReportReturPembelianForm_profilId").val(data.id);
    }

    function isiUser(data) {
        console.log(data);
        $("#user").val(data.namaLengkap + ' (' + data.nama + ')');
        $("#tabel-user").slideUp(500);
        $("#ReportReturPembelianForm_userId").val(data.id);
    }

    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>