<?= CHtml::beginForm($this->createUrl('tambahdetail', ['id' => $modelId]), 'post', ['class' => 'forminputmanual']) ?>
<?= CHtml::hiddenField('barcode', $data->barcode) ?>
<?= CHtml::hiddenField('tambah', 'tambah') ?>
<div class="row">
    <div class="medium-6 column">

        <?= CHtml::label('Pindahkan barang ke rak:', 'rak') ?>
        <?= CHtml::dropDownList('rak', null, CHtml::listData(
            RakBarang::model()->findAll(['order' => 'nama']),
            'id',
            'nama'
        ), ['empty' => 'Tidak dipindahkan']); ?>
    </div>
    <div class="medium-6 column">
        <label>&nbsp;</label>
        <?= CHtml::checkBox('setinaktif', false, ['']) ?>
        <?= CHtml::label('Non aktifkan barang', 'setinaktif') ?>
    </div>
</div>
<br />
<div class="row">
    <div class="column">
        <div class="row collapse">
            <div class="small-4 columns">
                <span class="prefix huruf"><b>Q</b>ty Asli</span>
            </div>
            <div class="small-4 columns">
                <input id="qty" type="number" name='qty' accesskey="q" autocomplete="off" />
            </div>
            <div class="small-4 columns">
                <!-- <a id="tombol-ok-tambah" href="" class="button postfix">Tambah</a> -->
                <input type="submit" class="button postfix" value="Tambah" />
            </div>
        </div>
    </div>
</div>
<?= CHtml::endForm() ?>