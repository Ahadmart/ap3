<?php
/* @var $this PenerimaanController */
/* @var $model Penerimaan */

$this->breadcrumbs = array(
    'Penerimaan' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Penerimaan: {$model->nomor}";
?>
<div class="row">
    <div class="large-12 columns">
        <?php
        echo CHtml::ajaxLink('<i class="fa fa-gears"></i> P<span class="ak">r</span>oses', $this->createUrl('proses', array('id' => $model->id)), array(
            'data' => "proses=true",
            'type' => 'POST',
            'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();
                            }
                        }'
                ), array(
            'class' => 'tiny bigfont button',
            'accesskey' => 'r'
                )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_form', array(
                'model' => $model,
                'profil' => $profil
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_form_detail', array(
                'model' => $penerimaanDetail,
                'itemKeuangan' => $itemKeuangan,
                'hutangPiutang' => $hutangPiutang,
                'listNamaAsalHutangPiutang' => $listNamaAsalHutangPiutang,
                'listNamaTipe' => $listNamaTipe,
                'headerModel' => $model
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_detail', array(
                'model' => $detail,
                'headerModel' => $model
            ));
            ?>
        </div>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
            array('label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'accesskey' => 'h',
                    'submit' => array('hapus', 'id' => $model->id),
                    'confirm' => 'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
            array('label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'submit' => array('hapus', 'id' => $model->id),
                    'confirm' => 'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
