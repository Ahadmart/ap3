<h4>RRP</h4>
<hr />
<?php
$this->widget('BGridView', array(
    'id' => 'harga-jual-rekomendasi-grid',
    'dataProvider' => $rrp->search(),
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
