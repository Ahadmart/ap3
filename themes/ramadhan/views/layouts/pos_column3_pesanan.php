<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
    <!--<div id="logo">-->
       <!--<img src="<?php /* echo Yii::app()->theme->baseUrl.'/img/' */ ?>ahadmart-logo.png" />-->
    <!--</div>-->
    <ul class="stack button-group">
        <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny secondary button" accesskey="n" id="tombol-new"><span class="ak">N</span>ew</a></li>
        <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="expand bigfont tiny secondary button" accesskey="s"><span class="ak">S</span>uspended</a></li>
        <li><a href="<?php echo $this->createUrl('cekharga'); ?>" class="expand bigfont tiny secondary button" accesskey="h">Cek <span class="ak">H</span>arga</a></li>
        <li><a href="<?php echo $this->createUrl('pesanan'); ?>" class="expand bigfont tiny secondary button" accesskey="p"><span class="ak">P</span>esanan (Sales Order)</a></li>
    </ul>
    <?php if (!is_null($this->namaProfil)) {
        ?>
        <div id="data-customer">
            <nomor>Nomor: <?php echo $this->profil->nomor; ?></nomor>
            <nama><?php echo $this->namaProfil; ?></nama>
            <address>
                <?php echo!empty($this->profil->alamat1) ? $this->profil->alamat1 : ''; ?>
                <?php echo!empty($this->profil->alamat2) ? '<br>' . $this->profil->alamat2 : ''; ?>
                <?php echo!empty($this->profil->alamat3) ? '<br>' . $this->profil->alamat3 : ''; ?>
            </address>
        </div>
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
                dataUrl = '<?php echo $this->createUrl('pesananganticustomer', array('id' => $this->pesananId)); ?>';
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
        </script>
        <?php
    }
    ?>
</div>
<?php echo $content; ?>
<?php
$this->endContent();
