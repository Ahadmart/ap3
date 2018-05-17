<h4>Harga <small>Jual</small></h4>
<hr />
<?php
$this->widget('BGridView', array(
    'id' => 'harga-jual-grid',
    'dataProvider' => $hargaJual->search(),
    'columns' =>
    array(
        array(
            'name' => 'harga',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan')
        ),
        array(
            'name' => 'created_at',
            //'headerHtmlOptions' => array('class' => 'rata-kanan'),
            //'htmlOptions' => array('class' => 'rata-kanan')
        ),
    ),
));
