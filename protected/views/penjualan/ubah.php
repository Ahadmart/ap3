<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Penjualan';
?>
<div class="row">
    <div class="large-7 columns header">
        <?php
        if ($model->transfer_mode) {
            ?>
            <span class="warning label">Transfer Mode</span>
            <?php
        }
        ?>
        <span class="secondary label">Customer</span><span class="label"><?php echo $model->profil->nama; ?></span><br />
        <span class="secondary label label-total">Total</span><span class="label label-total" id="total-penjualan"><?php echo $model->total; ?></span>
    </div>
    <div class="large-5 columns">
        <ul class="button-group right">
            <li>
                <button href="#" accesskey="p" data-dropdown="printinvoice" aria-controls="printinvoice" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text fa-fw"></i> <span class="ak">P</span>rint Draft Invoice (rrp)</button><br>
                <ul id="printinvoice" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerInvoiceRrp as $printer) {
                        ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printdraftinvoice', array('id' => $model->id, 'printId' => $printer['id'])) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <?php
                echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Penjualan', $this->createUrl('simpanpenjualan', array('id' => $model->id)), array(
                    'data' => "simpan=true",
                    'type' => 'POST',
                    'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();;
                            }
                        }'
                        ), array(
                    'class' => 'tiny bigfont button',
                    'accesskey' => 's'
                        )
                );
                ?>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <?php
    $this->renderPartial('_input_detail', array(
        'penjualan' => $model,
    ));
    ?>
</div>
<div class="row" id="penjualan-detail">
    <?php
    $this->renderPartial('_detail', array(
        'penjualan' => $model,
        'penjualanDetail' => $penjualanDetail
    ));
    ?>
</div>
<div class="row" id="barang-list" style="display:none">
    <?php
    $this->renderPartial('_barang_list', array(
        'barang' => $barang,
    ));
    ?>
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
