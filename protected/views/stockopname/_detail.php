<div class="small-12  columns">
   <?php
   $this->widget('BGridView', array(
       'id' => 'so-detail-grid',
       'dataProvider' => $modelDetail->search(),
       'columns' => array(
           array(
               'name' => 'barcode',
               'value' => '$data->barang->barcode',
               'headerHtmlOptions' => array('class' => 'hide-for-small-only'),
               'htmlOptions' => array('class' => 'hide-for-small-only'),
           ),
           array(
               'name' => 'namaBarang',
               'value' => '$data->barang->nama'
           ),
           array(
               'name' => 'qty_tercatat',
               'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
           ),
           array(
               'name' => 'qty_sebenarnya',
               'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
           ),
           array(
               'class' => 'BButtonColumn',
               'template' => '{delete}',
               'deleteButtonUrl' => 'Yii::app()->controller->createUrl("stockopname/hapusdetail", array("id"=>$data->primaryKey))',
               'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
           ),
       ),
   ));
   ?>
</div>