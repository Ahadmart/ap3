<?php
$this->boxHeader['small'] = 'Import';
$this->boxHeader['normal'] = 'Import Pembelian dari AhadPOS 2';
?>
<form method="POST">
   <div class="row">
      <div class="large-6 columns">
         <div class="row collapse postfix-round">
            <div class="small-9 columns">
               <input type="text" name="nomor" placeholder="Masukkan Nomor Pembelian AhadPOS2" autofocus="autofocus">
            </div>
            <div class="small-3 columns">
               <input class="button postfix" type="submit" value="Go" />
            </div>
         </div>
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
