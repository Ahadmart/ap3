<?php
/**
 * @var Pembelian $pembelianModel
 */

$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'edit-detail-form',
    'action'               => $this->createUrl('editdetail', ['id' => $pembelianModel->id]),
    'enableAjaxValidation' => false,
]);
?>
<input type="hidden" name="detail-id" id="edit-detail-id" value="" />
<input type="hidden" name="barang-id" id="edit-barang-id" value="" />
<input type="hidden" name="edit-detail" value="1" />
<div class="panel" style="margin-bottom:0">
    <h5><span id="edit-barang-info"></span></h5>
    <div class="response" style="display:none"></div>
    <div class="row">
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('J<u><b>u</b></u>mlah yang dibeli', 'edit-qty') ?>
            <div class="row collapse">
                <div class="small-9 columns">
                    <?php echo CHtml::numberField('qty', '', ['accesskey' => 'u', 'autocomplete' => 'off', 'id' => 'edit-qty']); ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix"><b><span id="edit-satuan"></span></b></span>
                </div>
            </div>
            <?php echo CHtml::label('Sub Total', 'edit-subtotal') ?>
            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix"><b>Rp.</b></span>
                </div>
                <div class="small-9 columns">
                    <?php echo CHtml::numberField('subtotal', '', ['id' => 'edit-subtotal']); ?>
                </div>
            </div>
        </div>
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('PPN', 'ppn') ?>
            <div class="row collapse">
                <div class="small-9 columns">
                    <?php echo CHtml::numberField('ppn', '', ['id' => 'edit-ppn']); ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix"><b>%</b></span>
                </div>
            </div>
            <?php echo CHtml::label('Profit', 'profit') ?>
            <div class="row collapse">
                <div class="small-9 columns">
                    <?php echo CHtml::numberField('profit', '', ['id' => 'edit-profit']); ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix"><b>%</b></span>
                </div>
            </div>
        </div>
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('Diskon', 'diskonp') ?>
            <div class="row collapse">
                <div class="small-9 columns">
                    <?php echo CHtml::numberField('diskonp', '', ['id' => 'edit-diskonp']); ?>
                </div>
                <div class="small-3 columns">
                    <span class="postfix"><b>%</b></span>
                </div>
            </div>
        </div>
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('Diskon', 'diskonr') ?>
            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix"><b>Rp.</b></span>
                </div>
                <div class="small-9 columns">
                    <?php echo CHtml::numberField('diskonr', '', ['id' => 'edit-diskonr']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="small-12 medium-6 medium-centered columns">
            <a href="#" class="button tiny bigfont small-12 columns" accesskey="7" id="edit-hitung-harga">Hitung Harga (Alt+7)</a>
        </div>
    </div>
    <div class="row">
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('Harga Beli', 'hargabeli', ['id' => 'edit-label-harga-beli']) ?>
            <?php echo CHtml::numberField('hargabeli', '', ['id' => 'edit-harga-beli', 'autocomplete' => 'off']); ?>
        </div>
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('Harga Jual', 'hargajual', ['id' => 'edit-label-harga-jual']) ?>
            <?php echo CHtml::numberField('hargajual', '', ['id' => 'edit-harga-jual', 'style' => 'margin-bottom:0', 'autocomplete' => 'off']); ?>
            <?php echo CHtml::label('test', '', ['id' => 'edit-harga-jual-raw']); ?>
        </div>
        <div class="medium-6 large-4 columns">
            <?php echo CHtml::label('Tanggal Expire', 'tanggal_kadaluwarsa') ?>
            <?php echo CHtml::textField('tanggal_kadaluwarsa', '', ['class' => 'tanggalan']); ?>
        </div>
    </div>
    <?php
    if (!empty($skemaHJ)) :
    ?>
        <hr />
        <h6>Skema Harga Jual <small>input</small></h6>
        <div class="row">
            <?php
            foreach ($skemaHJ as $skema) :
            ?>
                <div class="medium-6 large-3 end columns">
                    <?php echo CHtml::label($skema->nama, 'skemahj_' . $skema->id); ?>
                    <?php echo CHtml::numberField('skemahj[' . $skema->id . ']', '', ['autocomplete' => 'off', 'class' => 'input-shj']); ?>
                </div>
            <?php
            endforeach;
            ?>
        </div>
        <hr />
    <?php
    endif;
    ?>
    <div class="row">
        <div class="span-12 columns">
            <?php
            $focusSetelahTambah = isset($tipeCari) && $tipeCari > 1 ? '#barcode-pilih' : '#scan';
            echo CHtml::ajaxSubmitButton('Simpan (Alt+n)', $this->createUrl('simpaneditbarang', [
                'id' => $pembelianModel->id,
            ]), [
                'type'    => 'POST',
                'success' => "function () {
                                        $.fn.yiiGridView.update('pembelian-detail-grid');
                                        updateTotal();
                                        $('{$focusSetelahTambah}').focus();
                                        $('#edit-modal').foundation('reveal', 'close');
                                    }",
            ], [
                'id'        => 'tombol-simpan-edit',
                'class'     => 'tiny bigfont button',
                'accesskey' => 'n',
            ]);
            ?>
            <a class="tiny bigfont button" id="tombol-batal" href="#" accesskey="l" onclick="$('#edit-modal').foundation('reveal', 'close')">Bata<span class="ak">l</span></a>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>