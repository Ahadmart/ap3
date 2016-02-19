<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
    <!--<div id="logo">-->
       <!--<img src="<?php /* echo Yii::app()->theme->baseUrl.'/img/' */ ?>ahadmart-logo.png" />-->
    <!--</div>-->
    <ul class="stack button-group">
        <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny button" accesskey="n"><span class="ak">N</span>ew</a></li>
        <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="success expand bigfont tiny button" accesskey="s"><span class="ak">S</span>uspended</a></li>
    </ul>
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
        <span class="secondary label" id="label-customer"><a accesskey="c"><span class="ak">C</span>ustomer</span></a>
    <div id="data-customer">
        <nomor>Nomor: <?php echo $this->profil->nomor; ?></nomor>
        <nama><?php echo $this->namaProfil; ?></nama>
        <address>
            <?php echo!empty($this->profil->alamat1) ? $this->profil->alamat1 : ''; ?>
            <?php echo!empty($this->profil->alamat2) ? '<br>' . $this->profil->alamat2 : ''; ?>
            <?php echo!empty($this->profil->alamat3) ? '<br>' . $this->profil->alamat3 : ''; ?>
        </address>
    </div>
    <form id="form-admin-login">
        <div class="row admin-input" style="display: none">
            <div class="small-12">
                <input type="text"  name="admin-user" id="admin-user" placeholder="Nama user"/>
            </div>
        </div>
        <div class="row collapse admin-input" style="display: none">
            <div class="small-9 large-10 columns">
                <input type="password"  name="admin-password" id="admin-password" placeholder="Password"/>
            </div>
            <div class="small-3 large-2 columns">
                <a href="#" class="button postfix" id="tombol-admin-login"><i class="fa fa-check"></i></a>
            </div>
        </div>
    </form>
    <ul class="stack button-group">
        <li><a href="" class="expand bigfont tiny <?php echo Yii::app()->user->getState('kasirOtorisasiAdmin') ? 'warning' : 'secondary'; ?> button" id="tombol-admin-mode" accesskey="m">Mode Ad<span class="ak">m</span>in</a></li>
    </ul>
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

            $(document).on('click', "#tombol-admin-mode", function () {
                $(".admin-input").toggle(500, function () {
                    if ($(".admin-input").is(':visible')) {
                        $("#admin-user").focus();
                        console.log('admin user focus');
                    } else {
                        $("#scan").focus();
                        console.log('scan focus');
                    }
                });
                return false;
            });
        });

        $("#tombol-ganti-customer").click(function () {
            $("#form-nomor-customer").submit();
        });

        $("#tombol-admin-login").click(function () {
            $("#form-admin-login").submit();
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
                        $("#data-customer nomor").html('Nomor: ' + data.nomor);
                        $("#data-customer nama").html(data.nama);
                        $("#data-customer address").html(data.address);
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

        $("#form-admin-login").submit(function () {
            dataUrl = '<?php echo $this->createUrl('adminlogin', array('id' => $this->penjualanId)); ?>';
            dataKirim = {
                usr: $("#admin-user").val(),
                pwd: $("#admin-password").val()
            };

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        console.log('Manual Mode: ON');
                        location.reload();
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000,
                        });
                    }
                    $("#admin-user").val("");
                    $("#admin-password").val("");
                    $(".admin-input").hide(500);
                    $("#scan").focus();
                }
            });
            return false;
        });
    </script>
    <?php
}
?>
</div>
<?php echo $content; ?>
<?php
$this->endContent();
