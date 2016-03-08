<?php
$this->boxHeader['small'] = 'Import';
$this->boxHeader['normal'] = 'Import Penjualan dari AhadPOS 2';
?>
<form method="POST">
    <div class="row">
        <div class="large-6 columns">
            <div class="row collapse postfix-round">
                <div class="small-9 columns">
                    <input type="text" name="nomor" placeholder="Masukkan Nomor Penjualan AhadPOS2" autofocus="autofocus">
                </div>
                <div class="small-3 columns">
                    <input class="button postfix" type="submit" value="Go" />
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="medium-6 columns">
            <label for="db_source">Database ahadPOS 2</label>
            <input id="db_source" type="text" name="database" value="gudang" placeholder="Database ahadpos2 (sumber)" />
        </div>
    </div>
</form>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
