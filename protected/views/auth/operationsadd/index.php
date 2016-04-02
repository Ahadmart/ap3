<?php
/* @var $this OperationsaddController */

$this->breadcrumbs = array(
    'Operations add',
);
$this->boxHeader['small'] = 'Tambah';
$this->boxHeader['normal'] = 'Tambah Item Otorisasi (type: operations)';
?>

<div class="form">
    <form method="POST">
        <div class="row">
            <div class="small-12 columns">
                <textarea name="operations" rows="9" autofocus="autofocus" ></textarea>
            </div>
        </div>

        <div class="row">
            <div class="small-12 columns">
                <p><input type="submit" class="tiny bigfont button" name="submit" value="Submit" /></p>
            </div>
        </div>
    </form>
</div>
<?php if (!empty($hasil)):
    ?>
    <div class="row">
        <div class="small-12 columns">
            <div class="panel">
                <?php echo $hasil; ?>
            </div>
        </div>
    </div>
    <?php

endif;
