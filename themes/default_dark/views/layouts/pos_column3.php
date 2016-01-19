<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
    <!--<div id="logo">-->
       <!--<img src="<?php /* echo Yii::app()->theme->baseUrl.'/img/' */ ?>ahadmart-logo.png" />-->
    <!--</div>-->
    <?php if (!is_null($this->namaProfil)) {
        ?>
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
        <span class="secondary label" id="label-customer"><a accesskey="c"><span class="ak">C</span>ustomer</span></a><span class="label" id="nama-customer"><?php echo $this->namaProfil; ?></span>
    <table id="tabel-customer">
        <tr>
            <td>Nomor : <?php echo $this->profil->nomor; ?></td>
        </tr>
        <tr>
            <td>Alamat :</td>
        </tr>
        <tr>
            <td>
                <?php echo $this->profil->alamat1; ?><br />
                <?php echo $this->profil->alamat2; ?><br />
                <?php echo $this->profil->alamat3; ?>
            </td>
        </tr>
    </table>
    <script>

        $(function () {
            $(document).on('click', "#label-customer", function () {
                $("#ganti-customer").toggle(500, function () {
                    if ($("#ganti-customer").is(':visible')) {
                        $("#nomor-customer").focus();
                        console.log('nomor focus');
                    } else {
                        $("#scan").focus();
                        console.log('scan focus');
                    }
                });
            });
        });

        $("#tombol-ganti-customer").click(function () {
            $("#form-nomor-customer").submit();
        });

        $("#form-nomor-customer").submit(function () {
            dataUrl = '<?php echo $this->createUrl('ganticustomer', array('id' => $this->penjualanId)); ?>';
            dataKirim = {nomor: $("#nomor-customer").val()};

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $("#nama-customer").html(data.nama);
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                        });
                    }
                    $("#nomor-customer").val("");
                    $("#ganti-customer").hide(500);
                    $("#scan").focus();
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
