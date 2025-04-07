<?php
/* @var $this CustomerdisplayController */

// $this->breadcrumbs = array(
//     'Customerdisplay',
// );
$logoSrc = Yii::app()->theme->baseUrl . '/img/logo.png';
if (!empty($logo)) {
    $logoSrc = $logo;
}
?>

<div class="row" style="height: 25vh">
    <div class="medium-8 columns box kiri_atas">
        <div id="welcome" class="idle">
            <a href="<?php echo Yii::app()->baseUrl; ?>">
                <img src="<?= $logoSrc ?>" alt="logo" />
            </a>
            <h1>Selamat datang di <?= $namaToko ?></h1>
        </div>
        <div id="last_scan" class="proc">
            <p>Nama Barang</p>
            <p><span>Harga</span><span>:</span><span class="hj"></span><span class="hj_dis"></span></p>
            <p><span>Subtotal</span><span>:</span><span class="stotal"></span><span class="stotaldis"></span></p>
        </div>
    </div>
    <div class="medium-4 columns box kanan_atas">
        <div id="info_kasir" class="idle">
            <p>Anda sedang dilayani oleh</p>
            <p><?= "{$user['namaLengkap']}" //[#{$user['id']}]"
                ?></p>
            <p>Selamat berbelanja di <?= $namaToko ?></p>
        </div>
        <div id="info_cust" class="proc">
            <img src="<?= $logoSrc ?>" alt="logo" />
            <p>Ahlan wa Sahlan!</p>
            <p>Silahkan input nomor</p>
            <p>member anda</p>
        </div>
        <div id="info_checkout" class="checkout">
            <img src="<?= $logoSrc ?>" alt="logo" />
            <p>Terima kasih!</p>
        </div>
    </div>
</div>
<div class="row" style="height:75vh">
    <div class="medium-8 columns box kiri_bawah">
        <div class="idle">
            <?php
            /*
<h4><?= $ws['ip'] ?></h4>
<p>User: <?= $user['namaLengkap'] ?> (<?= $user['id'] ?>)</p>
 */
            // var_dump($brosurs);
            ?>
        </div>
        <div id="brosur-container">
            <img />
        </div>
    </div>
    <div class="medium-4 columns box kanan_bawah">
        <div class="network_status">
            <figure class="sinyal mati"></figure>
        </div>
        <div id="time_board" class="idle">
            <p id="waktu"><span id="jam"></span><span id="separator">:</span><span id="menit"></span></p>
            <p id="tanggal"></p>
            <hr />
            <p class="caption_selanjutnya"></p>
            <p class="waktu_selanjutnya"></p>
            <p class="sholat_selanjutnya"></p>
            <jadwal_sholat>
                <p>Jadwal Sholat Hari Ini</p>
                <?php
                $waktu    = $jadwal['timings'];
                $wSubuh       = substr($waktu['Fajr'], 0, 5);
                $wSyuruq      = substr($waktu['Sunrise'], 0, 5);
                $wZhuhur      = substr($waktu['Dhuhr'], 0, 5);
                $wAshar       = substr($waktu['Asr'], 0, 5);
                $wMaghrib     = substr($waktu['Maghrib'], 0, 5);
                $wIsya        = substr($waktu['Isha'], 0, 5);
                ?>
                <span class="nama">Subuh / الفجر</span><span class="waktu"><?= $wSubuh ?></span>
                <span class="nama">Syuruq / الشروق</span><span class="waktu"><?= $wSyuruq ?></span>
                <span class="nama">Zuhur / الظُهر</span><span class="waktu"><?= $wZhuhur ?></span>
                <span class="nama">'Ashar / العصر</span><span class="waktu"><?= $wAshar ?></span>
                <span class="nama">Maghrib / المغرب</span><span class="waktu"><?= $wMaghrib ?></span>
                <span class="nama">Isya' / العِشاء</span><span class="waktu"><?= $wIsya ?></span>
            </jadwal_sholat>
        </div>
        <div id="detail_tr" class="proc">
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
            <div id="total_container">
                <span class="tarik_tunai">Tarik Tunai</span><span class="tarik_tunai tarik_tunai_val"></span>
                <span class="total">Total</span><span class="total total_val">50.000</span>
            </div>
        </div>
        <div id="payment" class="checkout">
            <span class="total">Total</span><span class="total">58.000</span>
            <span>Cash</span><span>15.000</span>
            <span>BSI</span><span>48.000</span>
            <span>Mandiri Kartu Kredit</span><span>48.000</span>
            <span class="payment_tt">Tarik Tunai</span><span class="payment_tt">100.000</span>
            <span class="kembalian">Kembalian</span><span class="kembalian">5.000</span>
            <p>Sampai bertemu lagi!</p>
        </div>
    </div>
</div>
<script>
    var brosur = <?= $brosurs ?>;
    var curBrosur = 0;
    var brosurIntervalID;

    $(document).ready(function() {
        $(".idle").show();
        $(".proc").hide();
        $(".checkout").hide();
        $(".tarik_tunai").hide();
        // $(".idle").hide();
        // $(".proc").show();
        // $(".checkout").show();
        changeBrosur();
        connectWebSocket();
        setInterval('updateTimeBoard()', 1000);

    })

    function changeBrosur() {
        var jmlBrosur = window.brosur.length;
        // console.log("Jumlah Brosur: " + jmlBrosur);
        if (brosurIntervalID) {
            clearInterval(brosurIntervalID);
            $("#brosur-container>img").attr('src', '');
        }
        if (jmlBrosur > 0) {
            $("#brosur-container img").attr('src', brosur[curBrosur]);
            brosurIntervalID = setInterval(function() {
                $("#brosur-container img").attr('src', brosur[curBrosur]);
                curBrosur++
                if (curBrosur >= jmlBrosur) {
                    curBrosur = 0
                }
                // console.log('CurBrosur: ' + curBrosur + ' (' + (parseInt(curBrosur) + 1) + '/' + jmlBrosur + ')');
                var imgSrc = window.brosur[curBrosur];
                // console.log(imgSrc);
                if (jmlBrosur > 1) {
                    $("#brosur-container>img").fadeOut(700, function() {
                        $("#brosur-container>img").attr('src', imgSrc);
                        $("#brosur-container>img").fadeIn(700);
                    });
                }
            }, 5000)
        }
    }

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

        let waktuSholat = {
            'Subuh': '<?= $wSubuh ?>',
            'Syuruq': '<?= $wSyuruq ?>',
            'Zhuhur': '<?= $wZhuhur ?>',
            'Ashar': '<?= $wAshar ?>',
            'Maghrib': '<?= $wMaghrib ?>',
            'Isya': '<?= $wIsya ?>'
        };
        let saatIni = currentHours + ':' + currentMinutes
        let waktuSelanjutnya = null
        let sholatSelanjutnya = null
        // console.log('Saat ini: ' + saatIni)
        Object.entries(waktuSholat).forEach(([nama, waktu]) => {
            // console.log('Waktu: ' + waktu)
            if (waktu > saatIni && waktuSelanjutnya == null) {
                waktuSelanjutnya = waktu
                sholatSelanjutnya = nama
            }
        })
        var selisih = null
        if (waktuSelanjutnya == null) {
            // Berarti sekarang sudah lewat Isya'
            waktuSelanjutnya = '<?= $wSubuh ?>'
            sholatSelanjutnya = 'Subuh'
            selisih = minsToStr(strToMins('24:00') - strToMins(saatIni) + strToMins(waktuSelanjutnya))
        } else {
            selisih = minsToStr(strToMins(waktuSelanjutnya) - strToMins(saatIni))
        }
        // console.log('Selanjutnya: ' + waktuSelanjutnya)
        // console.log(waktuSelanjutnya + '(-' + selisih + ')')
        if (waktuSelanjutnya) {
            $(".caption_selanjutnya").html('Waktu Sholat selanjutnya')
            $(".waktu_selanjutnya").html(waktuSelanjutnya + ' (-' + selisih + ')')
            $(".sholat_selanjutnya").html(sholatSelanjutnya)
        }
    }

    function strToMins(t) {
        var s = t.split(":");
        return Number(s[0]) * 60 + Number(s[1]);
    }

    function minsToStr(t) {
        return Math.trunc(t / 60) + ':' + ('00' + t % 60).slice(-2);
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
            output.html('Kemungkinan Error: ' + e);
        }
    }

    function isValidUser(id) {
        return id == <?= $user['id'] ?>;
    }

    function parseMessage(data) {
        // console.log('User: ' + parsed.uId)
        if (data.tipe == "<?= AhadPosWsClient::TIPE_BROSUR_UPDATE ?>") {
            // console.log(data.imgs);
            window.brosur = data.imgs;
            changeBrosur();
        } else
        if (data.tipe == "<?= AhadPosWsClient::TIPE_LOGO_UPDATE ?>") {
            location.reload(true);
        } else {
            var userId = data.uId;
            if (isValidUser(userId)) {
                // console.log('User accepted!');
                if (data.tipe == "<?= AhadPosWsClient::TIPE_WINDOW_REFRESH ?>") {
                    location.reload(true);
                }
                placeVar(data)
            }
        }
    }

    function placeVar(data) {
        if (data.tipe == "<?= AhadPosWsClient::TIPE_PROCESS ?>") {
            // console.log("Tipe Process");
            $(".checkout").hide();
            $(".idle").fadeOut().promise().done(function() {
                $(".proc").fadeIn();
            });
            if (data.detail) {
                injectTabel(data.detail);
                injectLastScan(data.detail[data.detail.length - 1]);
            }
            if (data.total) {
                injectTotal(data.total)
            }
            if (data.tariktunai) {
                injectTarikTunai(data.tariktunai)
                $(".tarik_tunai").show();
            } else {
                $(".tarik_tunai").hide();
            }
            if (data.profil) {
                if (data.profil.mol) {
                    injectCustomer(data.profil.mol)
                } else {
                    injectCustomerUmum();
                }
            }
        } else if (data.tipe == "<?= AhadPosWsClient::TIPE_IDLE ?>") {
            // console.log("Tipe Idle")
            $(".checkout").hide();
            $(".proc").fadeOut().promise().done(function() {
                $(".idle").fadeIn();
            });
        } else if (data.tipe == "<?= AhadPosWsClient::TIPE_CHECKOUT ?>") {
            // console.log("Tipe Checkout")
            $(".idle").hide();
            $(".proc").fadeOut().promise().done(function() {
                $(".checkout").fadeIn();
            });
            injectPayment(data)
        }
    }

    function injectPayment(data) {
        $("#payment").html("");
        if (data.total) {
            totalText = '<span class="total">Total</span><span class="total">' + data.total + '</span>'
            $("#payment").append(totalText)
        }
        if (data.bayar) {
            data.bayar.forEach(akun => {
                bayarText = '<span>' + akun.nama + '</span><span>' + akun.jml + '</span>'
                $("#payment").append(bayarText)
            });
        }
        if (data.tarik_tunai && data.tarik_tunai.jml > 0) {
            tarikTunaiText = '<span class="payment_tt">Tarik Tunai</span><span class="payment_tt">' + data.tarik_tunai.jml + '</span>'
            $("#payment").append(tarikTunaiText)
        }
        if (data.kembalian && data.kembalian > 0) {
            kembalianText = '<span class="kembalian">Kembalian</span><span class="kembalian">' + data.kembalian + '</span>'
            $("#payment").append(kembalianText)
        }
        $("#payment").append('<p>Sampai bertemu lagi!</p>')

    }

    function injectTabel(detail) {
        // console.log("isi tabel")
        // console.log(detail)
        var tbody = $(".t_detail tbody");
        tbody.empty();
        if (detail) {
            for (let i = 0; i < detail.length; i++) {
                let barang = detail[i]
                let content = '<tr><td>' + barang.nama + '</td><td>' + barang.harga_jual + '</td><td>' + barang.diskon + '</td><td>' + barang.qty + '</td><td>' + barang.stotal + '</td></tr>'
                tbody.append(content)
                if (i == detail.length - 1) {
                    scrollToBottom();
                }
            }
        }
    }

    function scrollToBottom() {
        // console.log("Scroll to bottom")
        let tableContainer = $(".t_wrapper")
        tableContainer.animate({
            scrollTop: tableContainer.prop("scrollHeight")
        }, 600);
    }

    function injectLastScan(item) {
        if (item) {
            $("#last_scan p:nth-child(1)").html('<span class="scan-qty">' + item.qty + ' x</span> ' + item.nama)
            $(".hj").html(item.harga_jual)
            if (item.diskon && item.diskon > 0) {
                $(".hj_dis").html('(<span>' + item.diskon + '</span>)')
                $(".stotaldis").html('(<span>Hemat ' + item.stotaldiskon + '</span>)')
            } else {
                $(".hj_dis").html("");
                $(".stotaldis").html("");
            }
            $(".stotal").html(item.stotal)
        } else {
            $("#last_scan p:nth-child(1)").html("")
            $(".hj").html("")
            $(".hj_dis").html("")
            $(".stotal").html("")
            $(".stotaldis").html("")
        }
    }

    function injectTotal(value) {
        $(".total_val").html(value)
    }

    function injectTarikTunai(value) {
        if (value == 0) {
            $(".tarik_tunai").hide();
        } else {
            $(".tarik_tunai").show();
            $(".tarik_tunai_val").html(value)
        }
    }

    function injectCustomer(data) {
        $("#info_cust p").eq(0).html(data.namaLengkap);
        $("#info_cust p").eq(1).html('Level ' + data.level);
        $("#info_cust p").eq(2).html('Poin ' + data.poin + '    |    Koin ' + data.koin);
    }

    function injectCustomerUmum() {
        $("#info_cust p").eq(0).html('Ahlan wa Sahlan!');
        $("#info_cust p").eq(1).html('Silahkan input nomor');
        $("#info_cust p").eq(2).html('member anda');
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
            $(".proc").fadeOut().promise().done(function() {
                $(".checkout").hide();
                $(".idle").fadeIn();
            });

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