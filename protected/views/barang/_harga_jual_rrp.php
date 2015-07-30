<h4><small>Update</small> RRP</h4>
<hr />
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'rrp-form', 'enableAjaxValidation' => false,
        ));
?>
<div class="row">
    <div class="small-12 columns">
        <div class="row collapse">
            <div class="medium-9 columns">
                <input id="hj-input" type="text" name="rrp" />
            </div>
            <div class="medium-3 columns">
                <?php
                echo CHtml::ajaxSubmitButton('Update', $this->createUrl('updaterrp', array('id' => $barang->id)), array(
                    'success' => "function () {
                                $.fn.yiiGridView.update('rrp-grid');
                            }"
                        ), array(
                    'class' => 'button postfix',
                    'id' => 'tombol-update-rrp'));
                ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$this->widget('BGridView', array(
    'id' => 'rrp-grid',
    'dataProvider' => $rrp->search(),
    'columns' =>
    array(
        array(
            'name' => 'harga',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan')
        ),
        'created_at',
    ),
));
