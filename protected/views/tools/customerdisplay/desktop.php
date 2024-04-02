<?php
/* @var $this CustomerdisplayController */

// $this->breadcrumbs = array(
//     'Customerdisplay',
// );
?>

<div class="row" style="height: 25vh">
    <div class="medium-8 columns box kiri_atas">
        <div id="welcome" class="idle">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" />
            <h1>Selamat datang di <?= $namaToko ?></h1>
        </div>
        <div id="scan" class="proc" style="display:none" ;>
            <p>2 x Indomie goreng spc 80gr</p>
            <p><span>Harga</span><span>:</span><span>2.500</span><span>(-500)</span></p>
            <p><span>Subtotal</span><span>:</span><span>4.000</span></p>
        </div>
    </div>
    <div class="medium-4 columns box kanan_atas">
        <!-- <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" /> -->
        <div id="info_kasir" class="idle">
            <p>Anda sedang dilayani oleh</p>
            <p><?= "{$user['namaLengkap']} [#{$user['id']}]" ?></p>
            <p>Selamat berbelanja di <?= $namaToko ?></p>
        </div>
        <div id="info_cust" class="proc" style="display: none">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" />
            <p>Ahlan wa Sahlan!</p>
            <p>Silahkan input nomor</p>
            <p>member anda</p>
        </div>
    </div>
</div>
<div class="row" style="height:75vh">
    <div class="medium-8 columns box kiri_bawah" class="idle">
        <h4><?= $ws['ip'] ?></h4>
        <p>User: <?= $user['namaLengkap'] ?> (<?= $user['id'] ?>)</p>
    </div>
    <div class="medium-4 columns box kanan_bawah" class="idle">
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".idle").hide();
        $(".proc").show();
    })

    var ws = new WebSocket("ws://<?= $ws['ip'] ?>:<?= $ws['port'] ?>/");

    function showMessage(pesan) {
        var output = $(".box.kiri_bawah");
        // output.html(pesan + "<br />");
        try {
            var parsed = JSON.parse(pesan);
            // output.html(pesan);
            parseMessage(parsed);

        } catch (e) {
            // output.html += pesan + "<br />";
        }
    }

    function isValidUser(id) {
        return id == <?= $user['id'] ?>;
    }

    function parseMessage(data) {
        // console.log('User: ' + parsed.u_id)
        var userId = data.u_id;
        if (isValidUser(userId)) {
            console.log('User accepted!');
            placeVar(data)
        }
    }

    function placeVar(data) {
        if (data.tipe == "<?= AhadPosWsClient::TIPE_PROCESS ?>") {
            console.log("Tipe Process");
            $(".idle").fadeOut().promise().done(function() {
                $(".proc").fadeIn();
            });
        } else if (data.tipe == "<?= AhadPosWsClient::TIPE_IDLE ?>") {
            console.log("Tipe Idle");
            $(".proc").fadeOut().promise().done(function() {
                $(".idle").fadeIn();
            });
        }
    }
    ws.addEventListener("open", function(event) {
        console.log("Connected to server");
        showMessage("Connected to server");
    });
    ws.addEventListener("message", function(event) {
        showMessage(event.data);
    });
    ws.addEventListener("close", function(event) {
        showMessage("Disconnected from server");
    });
    ws.addEventListener("error", function(event) {
        showMessage("Error: " + event.data);
    });
</script>