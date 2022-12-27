<?php
/* @var $this KasirController */
/* @var $model Kasir */
/* @var $form CActiveForm */
?>

<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BDetailView', [
            'data'       => $model,
            'attributes' => [
                [
                    'name'  => 'user.nama',
                    'label' => 'Login',
                ],
                [
                    'name'  => 'user.nama_lengkap',
                    'label' => 'Nama',
                ],
                /*
        array(
        'name' => 'device.nama',
        'label' => 'Device'
        ),
         */
                [
                    'name'  => 'device.keterangan',
                    'label' => 'POS Client (Workstation)',
                ],
                //'waktu_buka',
                [
                    'name'  => 'waktu_buka',
                    'value' => date_format(date_create_from_format('Y-m-d H:i:s', $model->waktu_buka), 'd-m-Y H:i:s'),
                ],
                [
                    'name'  => 'saldo_awal',
                    'value' => number_format($model->saldo_awal, 0, ',', '.'),
                ],
                [
                    'name'    => 'total_penjualan',
                    'value'   => number_format($model->total_penjualan, 0, ',', '.'),
                    'visible' => false,
                ],
                [
                    'name'    => 'total_margin',
                    'value'   => number_format($model->total_margin, 0, ',', '.'),
                    'visible' => false,
                ],
                [
                    'name'    => 'total_retur',
                    'value'   => number_format($model->total_retur, 0, ',', '.'),
                    'visible' => $model->total_retur != 0,
                    'visible' => false,
                ],
                [
                    'name'    => 'total_diskon_pernota',
                    'value'   => number_format($model->total_diskon_pernota, 0, ',', '.'),
                    'visible' => $model->total_diskon_pernota != 0,
                    'visible' => false,
                ],
                [
                    'name'    => 'total_infaq',
                    'value'   => number_format($model->total_infaq, 0, ',', '.'),
                    'visible' => $model->total_infaq != 0,
                    'visible' => false,
                ],
                [
                    'name'    => 'total_tarik_tunai',
                    'value'   => number_format($model->total_tarik_tunai, 0, ',', '.'),
                    'visible' => $model->total_tarik_tunai != 0,
                    'visible' => false,
                ],
                [
                    'name'    => 'total_koincashback_dipakai',
                    'value'   => number_format($model->total_koincashback_dipakai, 0, ',', '.'),
                    'visible' => $model->total_koincashback_dipakai != 0,
                    'visible' => false,
                ],
                [
                    'label'   => 'Total Penerimaan',
                    'value'   => number_format($model->total_penerimaan, 0, ',', '.'),
                    'visible' => $model->total_penerimaan != $model->total_penjualan,
                    'visible' => false,
                ],
                [
                    'label' => 'Total Penerimaan Kas',
                    'value' => number_format($penerimaanKas, 0, ',', '.'),
                    //'visible' => $model->total_penerimaan != $model->total_penjualan,
                    'visible' => false,
                ],
                [
                    'label'   => 'Saldo Akhir (Kas) Seharusnya',
                    'value'   => number_format($model->saldo_akhir_seharusnya, 0, ',', '.'),
                    'visible' => false,
                ],
                /*
    'saldo_akhir',
    'total_penjualan',
    'total_margin',
    'total_retur',
    'saldo_akhir_seharusnya',
     *
     */
            ],
        ]);
        ?>
    </div>
</div>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'kasir-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'saldo_akhir'); ?>
            <?php echo $form->textField($model, 'saldo_akhir', ['size' => 18, 'maxlength' => 18, 'autofocus' => 'autofocus']); ?>
            <?php echo $form->error($model, 'saldo_akhir', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton('Tutup Kasir', ['class' => 'tiny bigfont button']); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>