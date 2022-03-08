<?php
/* @var $this PosController */
/* @var $model So */

//$this->boxHeader['small'] = 'Suspended';
//$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Suspended';

?>
<script>
    $(function() {
        //$("#tombol-new").focus();
    });
</script>
<div class="row collapse">
    <div class="small-12 columns"">
    <a href=" <?= $this->createUrl('pesananbaru') ?>" class="right bigfont tiny button" id="tombol-baru" accesskey="r">Pesanan Ba<span class="ak">r</span>u</a>
        <?php
        $this->widget(
            'BGridView',
            [
                'id'            => 'pesanan-grid',
                'dataProvider'  => $model->search(),
                'filter'        => $model,
                'itemsCssClass' => 'tabel-index',
                'template'      => '{items}{summary}{pager}',
                'columns'       => [
                    [
                        'class'     => 'BDataColumn',
                        'name'      => 'nomorTanggal',
                        'header'    => 'Nomor / Tang<span class="ak">g</span>al',
                        'accesskey' => 'g',
                        'type'      => 'raw',
                        'value'     => [$this, 'renderPesananColumn']
                    ],
                    [
                        'name'  => 'namaProfil',
                        'value' => '$data->profil->nama'
                    ],
                    [
                        'header'            => 'Total',
                        'value'             => '$data->total',
                        'htmlOptions'       => ['class' => 'rata-kanan'],
                        'headerHtmlOptions' => ['class' => 'rata-kanan']
                    ],
                    [
                        'class'       => 'BDataColumn',
                        'name'        => 'tombolJual',
                        'header'      => '',
                        'type'        => 'raw',
                        'filter'      => false,
                        'value'       => [$this, 'renderPesananColumn'],
                        'htmlOptions' => ['title' => 'Jual'],
                    ],
                    [
                        'class'               => 'BButtonColumn',
                        'deleteButtonUrl'     => 'Yii::app()->controller->createUrl("salesorder/batal", array("id"=>$data->primaryKey))',
                        'deleteButtonOptions' => ['title' => 'Batal'],
                        'buttons'             => [
                            'delete' => [
                                'visible' => '$data->status == ' . So::STATUS_DRAFT . ' OR $data->status == ' . So::STATUS_PESAN,
                            ]
                        ]
                    ],
                ],
            ]
        );
        ?>
    </div>
</div>
<script>
    $(".link-jual").click(function() {
        $(this).unbind("click").html("Proses..");

        dataUrl = $(this).attr('href');
        dataKirim = {
            'pesan': true,
        };
        console.log(dataUrl);
        //printWindow = window.open('about:blank', '', 'left=20,top=20,width=400,height=600,toolbar=0,resizable=1');
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    if (data.penjualanId > 0) {
                        window.location.href = "<?= $this->createUrl('ubah', ['id' => '']); ?>" + data.penjualanId;
                    } else {
                        location.reload();
                    }
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                }
            }
        });
        return false;
    });
</script>