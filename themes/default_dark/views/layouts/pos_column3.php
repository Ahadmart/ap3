<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
    <!--<div id="logo">-->
    <!--<img src="<?php /* echo Yii::app()->theme->baseUrl.'/img/' */ ?>ahadmart-logo.png"
    />-->
    <!--</div>-->
    <ul class="stack button-group">
        <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny button" accesskey="n" id="tombol-new"><span class="ak">N</span>ew</a></li>
        <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="expand bigfont tiny info button" accesskey="s"><span class="ak">S</span>uspended</a></li>
        <li><a href="<?php echo $this->createUrl('cekharga'); ?>" class="expand bigfont tiny button" accesskey="h">Cek <span class="ak">H</span>arga</a></li>
        <li><a href="<?php echo $this->createUrl('pesanan'); ?>" class="expand bigfont tiny info button" accesskey="p"><span class="ak">P</span>esanan (Sales Order)</a>
        </li>
    </ul>
    <?php if (!is_null($this->namaProfil)) {
    ?>
        <?php
        if ($this->showMember == 1) {
        ?>
            <form id="form-nomor-customer">
                <div class="row collapse" id="ganti-customer" style="display: none">
                    <div class="small-9 large-10 columns">
                        <input type="text" name="nomor-customer" id="nomor-customer" placeholder="Input nomor" autocomplete="off" />
                    </div>
                    <div class="small-3 large-2 columns">
                        <a href="#" class="button postfix" id="tombol-ganti-customer"><i class="fa fa-check"></i></a>
                    </div>
                </div>
            </form>
            <span class="label" id="label-customer" accesskey="e">Custom<span class="ak">e</span>r</span>
        <?php
        }
        ?>
        <?php
        if ($this->showMemberOL == 1) {
        ?>
            <form id="form-nomor-member">
                <div class="row collapse" id="ganti-member" style="display: none">
                    <div class="small-9 large-10 columns">
                        <input type="text" name="nomor-member" id="nomor-member" placeholder="No Member/No Telp" autocomplete="off" />
                    </div>
                    <div class="small-3 large-2 columns">
                        <a href="#" class="button postfix" id="tombol-ganti-member"><i class="fa fa-check"></i></a>
                    </div>
                </div>
            </form>
            <span class="label" id="label-member" accesskey="r">Membe<span class="ak">r</span> Online</span>
        <?php
        }
        ?>
        <?php
        if ($this->showMember == 1) {
        ?>
            <div id="data-customer">
                <nomor>Nomor: <?php echo $this->profil->nomor; ?>
                </nomor>
                <nama><?php echo $this->namaProfil; ?>
                </nama>
                <address>
                    <?php echo !empty($this->profil->alamat1) ? $this->profil->alamat1 : ''; ?>
                    <?php echo !empty($this->profil->alamat2) ? '<br>' . $this->profil->alamat2 : ''; ?>
                    <?php echo !empty($this->profil->alamat3) ? '<br>' . $this->profil->alamat3 : ''; ?>
                </address>
            </div>
        <?php
        }
        ?>
        <?php
        if ($this->showMemberOL == 1) {
        ?>
            <div id="data-member">
                <nomor>Member Online: <?= is_null($this->memberOnline) ? '' : $this->memberOnline->nomor ?> </nomor>
                <nama><?= is_null($this->memberOnline) ? '-' : $this->memberOnline->namaLengkap ?> </nama>
                <address><?= is_null($this->memberOnline) ? '' : $this->memberOnline->alamat ?> </address>
                <info><?= is_null($this->memberOnline) ? '' : 'Level: ' . $this->memberOnline->level .' ('.$this->memberOnline->levelNama . ')<br />Poin: ' . $this->memberOnline->poin . ', Koin: <span id="info-koinmol">' . $this->memberOnline->koin . '</span>' ?>
                </info>
            </div>
        <?php
        }
        ?>
        <form id="form-admin-login">
            <div class="row admin-input" style="display: none">
                <div class="small-12">
                    <input type="text" name="admin-user" id="admin-user" placeholder="Nama user" autocomplete="off" />
                </div>
            </div>
            <div class="row collapse admin-input" style="display: none">
                <div class="small-9 large-10 columns">
                    <input type="password" name="admin-password" id="admin-password" placeholder="Password" autocomplete="off" />
                </div>
                <div class="small-3 large-2 columns">
                    <a href="#" class="button postfix" id="tombol-admin-login"><i class="fa fa-check"></i></a>
                </div>
            </div>
        </form>
        <?php
        $posModeAdmin = Yii::app()->user->getState('posModeAdminAlwaysON');
        if (!$posModeAdmin) {
        ?>
            <ul class="stack button-group">
                <li><a href="" class="expand bigfont tiny <?php echo Yii::app()->user->getState('kasirOtorisasiAdmin') == $this->penjualanId ? 'warning' : ''; ?> button" id="tombol-admin-mode" accesskey="m">Mode Ad<span class="ak">m</span>in</a></li>
            </ul>
        <?php
        } ?>
        <form id="form-akm">
            <div class="row collapse akm-input" style="display: none">
                <div class="small-9 large-10 columns">
                    <input type="text" name="akm-no" id="akm-no" placeholder="No AKM" autocomplete="off" />
                </div>
                <div class="small-3 large-2 columns">
                    <a href="#" class="button postfix" id="tombol-akm-ok"><i class="fa fa-check"></i></a>
                </div>
            </div>
        </form>
        <ul class="stack button-group">
            <li><a href="" class="expand bigfont tiny button" id="tombol-akm" accesskey="k">Input A<span class="ak">K</span>M</a></li>
        </ul>
        <form id="form-pesanan">
            <div class="row collapse pesanan-input" style="display: none">
                <div class="small-9 large-10 columns">
                    <input type="text" name="pesanan-no" id="pesanan-no" placeholder="No pesanan" autocomplete="off" />
                </div>
                <div class="small-3 large-2 columns">
                    <a href="#" class="button postfix" id="tombol-pesanan-ok"><i class="fa fa-check"></i></a>
                </div>
            </div>
        </form>
        <ul class="stack button-group">
            <li><a href="" class="expand bigfont tiny button" id="tombol-pesanan" accesskey="i"><span class="ak">I</span>nput Pesanan</a></li>
        </ul>
        <script>
            $(function() {
                $(document).on('click', "#label-customer", function() {
                    $("#ganti-customer").toggle(500, function() {
                        if ($("#ganti-customer").is(':visible')) {
                            $("#nomor-customer").focus();
                            // console.log('nomor focus');
                        } else {
                            $("#scan").focus();
                            // console.log('scan focus');
                        }
                    });
                });
                $(document).on('click', "#label-member", function() {
                    $("#ganti-member").toggle(500, function() {
                        if ($("#ganti-member").is(':visible')) {
                            $("#nomor-member").focus();
                            // console.log('nomor focus');
                        } else {
                            $("#scan").focus();
                            // console.log('scan focus');
                        }
                    })
                    $("#ganti-member-by-telp").toggle(500)
                })
                <?php
                if (Yii::app()->user->getState('kasirOtorisasiAdmin') == $this->penjualanId) {
                ?>
                    $(document).on('click', "#tombol-admin-mode", function() {
                        dataUrl =
                            '<?php echo $this->createUrl('adminlogout'); ?>';
                        dataKirim = {
                            confirm: 1
                        };

                        $.ajax({
                            type: 'POST',
                            url: dataUrl,
                            data: dataKirim,
                            success: function(data) {
                                if (data.sukses) {
                                    console.log('Admin Mode: OFF');
                                    location.reload();
                                } else {
                                    $.gritter.add({
                                        title: 'Error ' + data.error.code,
                                        text: data.error.msg,
                                        time: 3000,
                                    });
                                }
                                $("#scan").focus();
                            }
                        });
                        return false;
                    });

                <?php
                } else {
                ?>
                    $(document).on('click', "#tombol-admin-mode", function() {
                        $(".admin-input").toggle(500, function() {
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
                <?php
                } ?>
                $(document).on('click', "#tombol-akm", function() {
                    $(".akm-input").toggle(500, function() {
                        if ($(".akm-input").is(':visible')) {
                            $("#akm-no").focus();
                        } else {
                            $("#scan").focus();
                        }
                    });
                    return false;
                });

                $(document).on('click', "#tombol-pesanan", function() {
                    $(".pesanan-input").toggle(500, function() {
                        if ($(".pesanan-input").is(':visible')) {
                            $("#pesanan-no").focus();
                        } else {
                            $("#scan").focus();
                        }
                    });
                    return false;
                });

            });

            $("#tombol-ganti-customer").click(function() {
                $("#form-nomor-customer").submit();
            });

            $("#tombol-ganti-member").click(function() {
                $("#form-nomor-member").submit();
            });

            $("#tombol-admin-login").click(function() {
                $("#form-admin-login").submit();
            });

            $(".admin-input input").keydown(function(e) {
                if (e.keyCode === 13) {
                    $("#form-admin-login").submit();
                }
            });

            $("#tombol-akm-ok").click(function() {
                $("#form-akm").submit();
            });

            $("#tombol-pesanan-ok").click(function() {
                $("#form-pesanan").submit();
            });

            $("#form-nomor-customer").submit(function() {
                dataUrl =
                    '<?php echo $this->createUrl('ganticustomer', ['id' => $this->penjualanId]); ?>';
                dataKirim = {
                    nomor: $("#nomor-customer").val()
                };

                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: dataKirim,
                    success: function(data) {
                        if (data.sukses) {
                            $("#data-customer nomor").html('Nomor: ' + data.nomor);
                            $("#data-customer nama").html(data.nama);
                            $("#data-customer address").html(data.address);

                            $.fn.yiiGridView.update('penjualan-detail-grid');
                            updateTotal();
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

            $("#form-nomor-member").submit(function() {
                $("#label-member").addClass("alert geleng")
                dataUrl =
                    '<?php echo $this->createUrl('gantimember', ['id' => $this->penjualanId]); ?>';
                dataKirim = {
                    nomor: $("#nomor-member").val()
                };

                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: dataKirim,
                    success: function(data) {
                        if (data.statusCode == 200) {
                            // var profil = data.data.profil;
                            // $("#data-member nomor").html('Member Online: ' + profil.nomor);
                            // $("#data-member nama").html(profil.namaLengkap);
                            // $("#data-member address").html(profil.alamat);
                            // $("#data-member info").html(profil.levelNama + ' | ' + profil.poin + ' | ' +
                            //     profil.cb)

                            // $.fn.yiiGridView.update('penjualan-detail-grid');
                            // updateTotal();
                            location.reload();
                        } else {
                            $.gritter.add({
                                title: 'Error ' + data.statusCode,
                                text: data.error.type + ': ' + data.error.description,
                                time: 5000,
                            });
                        }
                        $("#label-member").removeClass("alert geleng")
                        $("#nomor-member").val("");
                        $("#ganti-member").hide(500);
                        $("#scan").focus();
                    }
                });
                return false;
            });

            $("#form-admin-login").submit(function() {
                dataUrl =
                    '<?php echo $this->createUrl('adminlogin'); ?>';
                dataKirim = {
                    usr: $("#admin-user").val(),
                    pwd: $("#admin-password").val(),
                    id: <?php echo $this->penjualanId; ?>
                };

                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: dataKirim,
                    success: function(data) {
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

            $("#form-akm").submit(function() {
                dataUrl =
                    '<?php echo $this->createUrl('inputakm', ['id' => $this->penjualanId]); ?>';
                dataKirim = {
                    nomor: $("#akm-no").val()
                };

                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: dataKirim,
                    success: function(data) {
                        if (data.sukses) {
                            $.fn.yiiGridView.update('penjualan-detail-grid');
                            updateTotal();
                        } else {
                            $.gritter.add({
                                title: 'Error ' + data.error.code,
                                text: data.error.msg,
                                time: 3000,
                            });
                        }
                        $("#akm-no").val("");
                        $(".akm-input").hide(500);
                        $("#scan").focus();
                    }
                });
                return false;
            });

            $("#form-pesanan").submit(function() {
                dataUrl =
                    '<?php echo $this->createUrl('inputpesanan', ['id' => $this->penjualanId]); ?>';
                dataKirim = {
                    nomor: $("#pesanan-no").val()
                };

                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: dataKirim,
                    success: function(data) {
                        if (data.sukses) {
                            $.fn.yiiGridView.update('penjualan-detail-grid');
                            updateTotal();
                        } else {
                            $.gritter.add({
                                title: 'Error ' + data.error.code,
                                text: data.error.msg,
                                time: 3000,
                            });
                        }
                        $("#pesanan-no").val("");
                        $(".pesanan-input").hide(500);
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
