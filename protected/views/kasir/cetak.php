<div class="row">
    <div class="small-12 columns">
        <div class="panel" style="overflow: visible">
            <h4><small>Cetak</small> Rekap Kasir</h4>
            <hr/>
            <pre><?php echo $text; ?></pre>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        echo CHtml::link('<i class="fa fa-times"></i> Kemba<span class="ak">l</span>i', $this->createUrl('index'), [
            'class'     => 'secondary tiny bigfont button',
            'accesskey' => 'l'
        ]);
        ?>
    </div>
</div>
