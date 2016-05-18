<div class="row">
    <div class="medium-7 large-8 columns">
        <div class="row">
            <div class="small-12 columns">
                <div class="panel" style="background-color: rgba(0, 0, 0, 0.5);padding: 0.75rem;" >
                    <h4 style="font-weight: 400;color: #fff">&nbsp;
                        <span class="" id="view-nama"></span>
                    </h4>
                    <h4 style="font-weight: 400;color: #fff;">&nbsp;
                        <span class="" id="view-barcode"></span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <div class="panel" style="backgroundcolor: rgba(0, 0, 0, 0.5);padding: 0.75rem;" >
                    <h1 style="font-weight: 700;font-size: 48pt; color: #fff">&nbsp;<span class="right" id="view-harga"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="medium-5 large-4 columns">
        <div class="row" style="margin-bottom: 20px">
            <div class="small-12 columns">
                <input id="scan" type="text" placeholder="Scan Barcode.." style="background-color: rgba(0, 0, 0, 0.9);color:#4da74d; font-size: 1.3rem;" autofocus="autofocus" autocomplete="off"/>
            </div>
        </div>
        <div class="row">
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">7</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">8</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">9</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="alert large button expand keynum">C</a>
            </div>
        </div>
        <div class="row">
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">4</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">5</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">6</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="alert large button expand keynum"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">1</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">2</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="large button expand keynum">3</a>
            </div>
            <div class="small-3 columns">
                <a href="#" class="disabled secondary large button expand">&nbsp;</a>
            </div>
        </div>
        <div class="row">
            <div class="small-6 columns">
                <a href="#" class="large button expand keynum">0</a>
            </div>
            <div class="small-6 columns">
                <a href="#" class="warning large button expand keynum" id="enter">ENTER</a>
            </div>
        </div>
    </div>
</div>
<script>

    function isiView(data) {
        $("#view-barcode").html(data.barcode);
        $("#view-nama").html(data.nama);
        $("#view-harga").html(data.harga);
    }

    function kirimBarcode(barcode) {
        var dataKirim = {
            cekharga: true,
            barcode: barcode
        };
        $.ajax({
            type: "POST",
            url: '<?php echo $this->createUrl('cekbarcode'); ?>',
            data: dataKirim,
            dataType: "json",
            success: function (data) {
                if (data.sukses) {
                    isiView(data);
                    $("#scan").val("");
                    $("#scan").focus();
                }
                $("#enter").html('ENTER');
                $("#enter").removeClass('disable');
            }
        });
    }

    $('a.keynum').click(function (e) {
        var nilai = $(e.target).text();
        console.log(nilai);
        var barcode = $("#scan").val();
        if (nilai >= 0) {
            $("#scan").val(barcode + nilai);
        } else if (nilai === 'DEL') {
            $("#scan").val(barcode.substring(0, barcode.length - 1));
        } else if (nilai === 'C') {
            $("#scan").val("");
        } else if (nilai === 'ENTER') {
            $(this).html('Proses..');
            $(this).addClass('disable');
            kirimBarcode(barcode);
        }
        $("#scan").focus();
        return false;
    });

    $("#scan").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#enter").html('Proses..');
            $("#enter").addClass('disable');
            var barcode = $(this).val();
            kirimBarcode(barcode);
        }
        return false;
    });

    var s_saver;

    $('body').keydown(function () {
        clearTimeout(s_saver);

        s_saver = setTimeout(function () {
            window.location.href = "<?php echo $this->createUrl('screensaver'); ?>";
        }, 120000);

    });

    $(document).ready(function () {
        s_saver = setTimeout(function () {
            window.location.href = "<?php echo $this->createUrl('screensaver'); ?>";
        }, 120000);
    });
</script>