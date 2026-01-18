<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos_mobile_menu_pesanan'); ?>
    <!--<div id="logo">-->
    <!--<img src="<?php /* echo Yii::app()->theme->baseUrl.'/img/' */ ?>ahadmart-logo.png" />-->
    <!--</div>-->

    <?php if (!is_null($this->namaProfil)) {
    ?>
        
        <script>
            $(function() {
                $(document).on('click', "#label-customer", function() {
                    $("#ganti-customer").toggle(500, function() {
                        if ($("#ganti-customer").is(':visible')) {
                            $("#nomor-customer").focus();
                            console.log('nomor focus');
                        } else {
                            $("#scan").focus();
                            console.log('scan focus');
                        }
                    });
                });
                <?php
                if (Yii::app()->user->getState('kasirOtorisasiAdmin') == $this->penjualanId) {
                ?>
                    $(document).on('click', "#tombol-admin-mode", function() {
                        dataUrl = '<?php echo $this->createUrl('adminlogout'); ?>';
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
                }
                ?>
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
                dataUrl = '<?php echo $this->createUrl('ganticustomer', array('id' => $this->penjualanId)); ?>';
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

            $("#form-admin-login").submit(function() {
                dataUrl = '<?php echo $this->createUrl('adminlogin'); ?>';
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
                dataUrl = '<?php echo $this->createUrl('inputakm', ['id' => $this->penjualanId]); ?>';
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
                dataUrl = '<?php echo $this->createUrl('inputpesanan', ['id' => $this->penjualanId]); ?>';
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
<?php echo $content; ?>
<?php
$this->endContent();
