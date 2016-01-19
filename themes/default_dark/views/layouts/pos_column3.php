<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
    <!--<div id="logo">-->
       <!--<img src="<?php /* echo Yii::app()->theme->baseUrl.'/img/' */ ?>ahadmart-logo.png" />-->
    <!--</div>-->
    <?php if (!is_null($this->namaProfil)) {
        ?>
        <span class="secondary label" id="label-customer"><a accesskey="c"><span class="ak">C</span>ustomer</span></a><span class="label"><?php echo $this->namaProfil; ?></span>

    <form id="form-nomor-customer">
        <div class="row collapse" id="ganti-customer" style="display: none">
            <div class="small-9 large-10 columns">
                <input type="text"  name="nomor-customer" id="nomor-customer" placeholder="Input nomor" accesskey="r"/>
            </div>
            <div class="small-3 large-2 columns">
                <a href="#" class="button postfix" id="tombol-ganti-customer"><i class="fa fa-check"></i></a>
            </div>
        </div>
    </form>
    <script>
        $("#label-customer").click(function () {
            $("#ganti-customer").toggle(500);
        });
        $("#form-nomor-customer").submit(function () {
            dataUrl = '<?php echo $this->createUrl('ganticustomer', array('id' => $this->penjualanId)); ?>';
            dataKirim = {nomor: $("#nomor-customer").val()};
            console.log(dataUrl);

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {

                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                        });
                    }
                    $("#nomor-customer").val("");
                    $("#form-nomor-customer").hide(500);
                }
            });
            return false;
        });
    </script>
    <?php
}
?>
<ul class="stack button-group">
    <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny button" accesskey="n"><span class="ak">N</span>ew</a></li>
    <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="success expand bigfont tiny button" accesskey="s"><span class="ak">S</span>uspended</a></li>
</ul>
</div>
<?php echo $content; ?>
<?php
$this->endContent();
