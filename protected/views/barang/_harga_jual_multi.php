<h4><small>Update</small> Multi Harga Jual</h4>
<hr />
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'harga-jual-multi-form',
    'enableAjaxValidation' => false,
        ]);
?>
<div class="row">
    <div class="small-12 columns">
        <div class="row collapse">
            <div class="medium-3 columns">
                <?= $form->dropDownList($hjMultiModel, 'satuan_id', CHtml::listData(SatuanBarang::model()->findAll(['order'=>'nama']), 'id', 'nama'), ['prompt'=>'Pilih satuan..']) ?>
        </div>
            <div class="medium-3 columns">
                <?= $form->textField($hjMultiModel, 'qty', ['placeholder'=>'Isi', 'value'=>'']) ?>
        </div>
            <div class="medium-3 columns">
                <?= $form->textField($hjMultiModel, 'harga', ['placeholder' => 'Harga Satuan']) ?>
            </div>
            <div class="medium-3 columns">
                <?php
                echo CHtml::ajaxSubmitButton('Update', $this->createUrl('updatehargajualmulti', ['id' => $barang->id]), [
                    'success' => "function () {
                                $.fn.yiiGridView.update('harga-jual-grid');
                            }"
                        ], [
                    'class' => 'button postfix',
                    'id'    => 'tombol-update-hj'
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$this->widget('BGridView', [
    'id'           => 'harga-jual-grid',
    'dataProvider' => $hjMulti->search(),
    'columns'      => [
        [
            'name'  => 'satuan',
            'value' => '$data->satuan->nama',
        ],
        'qty',
        [
            'name'              => 'harga',
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
            'htmlOptions'       => ['class' => 'rata-kanan']
        ],
        [
            'name' => 'created_at',
        ],
    ],
]);
