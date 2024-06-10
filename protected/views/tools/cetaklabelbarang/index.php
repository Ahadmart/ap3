<?php
/* @var $this CetaklabelbarangController */

$this->breadcrumbs = [
    'Cetak Label Barang' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Cetak Label';
$this->boxHeader['normal'] = 'Cetak Label Barang';
?>
<div class="row" style="overflow: auto">
    <div class="small-12 medium-6 columns">
        <div class="panel">
            <ul class="tabs" data-tab>
                <li class="tab-title active"><a href="#panelpembelian">Pembelian</a></li>
                <li class="tab-title"><a href="#panelbarang">Barang</a></li>
            </ul>
            <div class="tabs-content">
                <div class="content active" id="panelpembelian">
                    <?php
                    $this->renderPartial('_pembelian', ['model' => $pembelian]);
                    ?>                
                </div>
                <div class="content" id="panelbarang">
                    <?php
                    $this->renderPartial('_barang_list', ['pembelian' => $pembelian, 'barang' => $barang]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="small-12 medium-6 columns">
        <div class="panel">
            <h4>Label <small>yang akan dicetak</small></h4>
            <hr />
            <?php
            $this->renderPartial('_label', ['labelBarang' => $labelBarang]);
            ?>
        </div>
    </div>
</div>

<script>
    function updateGrid() {
        $.fn.yiiGridView.update("pembelian-grid");
        $.fn.yiiGridView.update("label-barang-cetak-grid");
    }
</script>
