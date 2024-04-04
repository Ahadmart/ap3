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
    <div class="medium-8 columns box kiri_bawah">
        <div class="network_status">
            <figure class="sinyal mati"></figure>
        </div>
        <div class="idle">
            <h4><?= $ws['ip'] ?></h4>
            <p>User: <?= $user['namaLengkap'] ?> (<?= $user['id'] ?>)</p>
        </div>
    </div>
    <div class="medium-4 columns box kanan_bawah">
        <div id="time_board" class="idle">
            <p id="waktu"><span id="jam"></span><span id="separator">:</span><span id="menit"></span></p>
            <p id="tanggal"></p>
            <hr />
        </div>
        <div id="detail_tr" class="proc" style="display: none" ;>
            <div class="t_wrapper">
                <table class="t_detail" id="t_detail">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div>
                Total
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".idle").hide();
        $(".proc").show();
        connectWebSocket();
        setInterval('updateTimeBoard()', 1000);
    })

    function updateTimeBoard() {
        const currentTime = new Date();
        const currentDate = currentTime.getDate();
        const currentMonth = currentTime.toLocaleString('id-ID', {
            month: 'long'
        });
        const currentYear = currentTime.getFullYear();
        var currentHours = currentTime.getHours();
        var currentMinutes = currentTime.getMinutes();

        // Convert the hours component to 12-hour format if needed
        // currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = (currentHours == 0) ? 12 : currentHours;
        // Pad the hours and minutes with leading zeros
        currentHours = (currentHours < 10 ? "0" : "") + currentHours;
        currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;

        // Compose the string for display
        var currentDateString = currentDate + " " + currentMonth + " " + currentYear;
        // var currentTimeString = currentHours + ":" + currentMinutes;

        $("#time_board #jam").html(currentHours);
        $("#time_board #menit").html(currentMinutes);
        $("#time_board>#tanggal").html(currentDateString);
    }

    function showMessage(pesan) {
        var output = $(".box.kiri_bawah");
        // output.html(pesan + "<br />");
        try {
            var parsed = JSON.parse(pesan);
            // output.html(pesan);
            parseMessage(parsed);

        } catch (e) {
            console.log("Message not JSON")
            output.html(pesan + ' --- ' + e);
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
            isiTabel(data);
        } else if (data.tipe == "<?= AhadPosWsClient::TIPE_IDLE ?>") {
            console.log("Tipe Idle");
            $(".proc").fadeOut().promise().done(function() {
                $(".idle").fadeIn();
            });
        }
    }

    function isiTabel(data) {
        // console.log("isi tabel")
        var detail = data.detail
        // console.log(detail)
        var tbody = $(".t_detail tbody");
        tbody.empty();
        for (let i = 0; i < detail.length; i++) {
            let barang = detail[i]
            let content = '<tr><td>' + barang.nama + '</td><td>' + barang.harga_jual + '</td><td>' + barang.diskon + '</td><td>' + barang.qty + '</td><td>' + barang.stotal + '</td></tr>'
            tbody.append(content)
        }
        scrollToBottom();
    }

    function scrollToBottom() {
        console.log("Scroll to bottom")
        let tableContainer = $(".t_wrapper")
        tableContainer.animate({
            scrollTop :tableContainer.prop("scrollHeight")
        }, 2000);
    }

    let websocket;
    const url = 'ws://<?= $ws['ip'] ?>:<?= $ws['port'] ?>';

    function connectWebSocket() {
        websocket = new WebSocket(url);

        websocket.onopen = function() {
            $(".sinyal").removeClass("mati error").addClass("nyala");
            console.log('WebSocket connection established.');
        };

        websocket.onclose = function(event) {
            $(".sinyal").removeClass("nyala error").addClass("mati");
            console.log('WebSocket connection closed.');
            // Try to reconnect after a delay
            setTimeout(function() {
                console.log('Attempting to reconnect...');
                connectWebSocket();
            }, 3000);
        };

        websocket.onerror = function(error) {
            $(".sinyal").removeClass("nyala mati").addClass("error");
            console.error('WebSocket error:', error);
        };

        // Handle incoming messages
        websocket.onmessage = function(event) {
            showMessage(event.data);
        };
    }
</script>