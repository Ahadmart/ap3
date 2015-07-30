<h4><small>Ubah</small> Supplier</h4>
<hr />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'supplier-form', 'enableAjaxValidation' => false,
        ));
?>
<div class="row">
    <div class="small-12 columns">
        <div class="row collapse">
            <div class="medium-10 columns">
                <select id="select-supplier" name="supplier_id">
                    <?php
                    $this->renderPartial('_supplier_opt', array(
                        'listBukanSupplier' => $listBukanSupplier
                    ));
                    ?>
                </select>
            </div>
            <div class="medium-2 columns">
                <?php
                echo CHtml::ajaxSubmitButton('Tambah', $this->createUrl('tambahsupplier', array('id' => $model->id)), array(
                    'success' => "function () {
                                $.fn.yiiGridView.update('supplier-barang-grid');
                                updateSupplierOpt();
                            }"
                        ), array(
                    'class' => 'button postfix',
                    'id' => 'tombol-tambah'));
                ?>
            </div>

        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script>
    function updateSupplierOpt() {
        $("#select-supplier").load("<?php echo $this->createUrl('listbukansupplier', array('id' => $model->id)); ?>");
    }
</script>


<?php
$this->widget('BGridView', array(
    'id' => 'supplier-barang-grid',
    'dataProvider' => $supplierBarang->search(),
    //'filter' => $model,
    'columns' =>
    array(
        array(
            'name' => 'namaSupplier',
            'header' => 'Supplier',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->createUrl('/supplier/view', array('id' => $data->supplier_id)) . '">' . $data->supplier->nama . '</a>';
            },
        ),
        array(
            'name' => 'default',
            //'header' => 'Set as default',
            'headerHtmlOptions' => array('style' => 'width:50px; text-align:center'),
            'htmlOptions' => array('style' => 'text-align:center'),
            'type' => 'raw',
            'value' => function($data) {
                $return = '<a class="tombol-set-default" href="' . Yii::app()->createUrl('/barang/assigndefaultsup', array('id' => $data->id, 'barangId' => $data->barang_id)) . '"><i class="fa fa-square-o"><i></a>';
                if ($data->default == 1) {
                    $return = '<i class="fa fa-check-square-o"><i>';
                }
                return $return;
            },
        ),
        array(
            'class' => 'BButtonColumn',
            'headerHtmlOptions' => array('style'=> 'width:50px; text-align:center'),
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("removesupplier", array("id"=>$data->id))',
            'afterDelete' => 'function(link,success,data){ if(success) updateSupplierOpt(); }',
        ),
    ),
));

        /*
          $this->widget('BGridView', array(
          'id' => 'supplier-barang-grid',
          'dataProvider' => $supplierBarang->search(),
          //'filter' => $model,
          'columns' =>
          array(
          array(
          'name' => 'namaSupplier',
          'header' => 'Supplier',
          'value' =>
          function($data) {
          return '<a href="' . Yii::app()->createUrl('/supplier/ubah', array('id' => $data->supplier_id)) . '">' . $data->supplier->nama . '</a>';
          },
          'type' => 'raw'
          ),
          array(
          'name' => 'primer',
          'header' => 'Set as default',
          //'headerHtmlOptions' => array('style'=> 'width:50px; text-align:center'),
          'htmlOptions' => array('style' => 'text-align:center'),
          'value' => function($data) {
          $return = '<a class="tombol-set-default" href="' . Yii::app()->createUrl('/barang/assigndefaultsup', array('barangId' => $data->barang_id, 'supplierId' => $data->supplier_id)) . '"><i class="fa fa-check"><i></a>';
          if ($data->primer == 1) {
          $return = '';
          }
          return $return;
          },
          'type' => 'raw'
          ),
          array(
          'class' => 'BButtonColumn',
          'deleteButtonUrl' => 'Yii::app()->controller->createUrl("removesupplier", array("supplierId"=>$data->supplier_id,"barangId"=>$data->barang_id))',
          'afterDelete' => 'function(link,success,data){ if(success) updateSupplierOpt(); }',
          ),
          ),
          ));
         *
         */
        ?>
<script>
    $(function () {
        $(document).on('click', ".tombol-set-default", function () {
            dataUrl = $(this).attr("href");
            console.log(dataUrl);
            $.fn.yiiGridView.update('supplier-barang-grid', {
                type: 'POST',
                url: dataUrl,
                success: function () {
                    $.fn.yiiGridView.update('supplier-barang-grid');
                }
            });
            return false;
        });
    });
</script>