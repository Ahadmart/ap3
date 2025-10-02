<?php
/* @var $this CustomerdisplayController */

// $this->breadcrumbs = array(
//     'Customerdisplay',
// );
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/qrcode.min.js', CClientScript::POS_HEAD);

$logoSrc = Yii::app()->theme->baseUrl . '/img/logo.png';
if (! empty($logo)) {
    $logoSrc = $logo;
}
?>
<div id="qrcode-wrapper" class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <div id="qrislogo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 110.71" fill="currentColor">
            <title>QRIS</title>
            <g id="Layer_2" data-name="Layer 2">
                <g id="Layer_1-2" data-name="Layer 1">
                    <polygon points="287.71 67.86 287.71 58.33 287.71 39.29 259.14 39.29 240.09 39.29 240.09 29.76 287.71 29.76 287.71 10.71 240.09 10.71 211.52 10.71 211.52 29.76 211.52 39.29 211.52 58.33 240.09 58.33 259.14 58.33 259.14 67.86 211.52 67.86 211.52 86.91 259.14 86.91 287.71 86.91 287.71 67.86" />
                    <rect x="182.95" y="10.71" width="19.05" height="76.19" />
                    <polygon points="97.24 10.71 97.24 29.76 154.38 29.76 154.38 39.29 116.28 39.29 97.24 39.29 97.24 58.33 97.24 86.91 116.28 86.91 116.28 58.62 144.86 86.91 173.43 86.91 143.62 58.33 154.38 58.33 173.43 58.33 173.43 39.29 173.43 29.76 173.43 10.71 154.38 10.71 97.24 10.71" />
                    <path d="M40.09,58.33h19v-19h-19Zm4.77-14.28h9.52v9.52H44.86Z" />
                    <path d="M30.57,10.71H13.9a2.41,2.41,0,0,0-1.68.7,2.38,2.38,0,0,0-.7,1.69V84.52a2.38,2.38,0,0,0,.7,1.69,2.4,2.4,0,0,0,1.68.69H59.14v-19H30.57Z" />
                    <path d="M85.33,10.71H40.09V29.76H68.67V58.33h19V13.1A2.41,2.41,0,0,0,87,11.41,2.44,2.44,0,0,0,85.33,10.71Z" />
                    <rect x="68.67" y="67.86" width="19.05" height="42.86" />
                    <path d="M38.1,0H2.38A2.39,2.39,0,0,0,0,2.38V38.1H4.76v-31A2.39,2.39,0,0,1,7.14,4.76h31Z" />
                    <path d="M295.24,61.9v31a2.39,2.39,0,0,1-2.38,2.38h-31V100h35.72A2.39,2.39,0,0,0,300,97.62V61.9Z" />
                </g>
            </g>
        </svg>
    </div>
    <div id="qrcode-jml"></div>
    <div id="qrcode"></div>
    <h4 id="ket">Scan QR untuk bayar</h4>
</div>
<div class="row" style="height: 25vh">
    <div class="medium-8 columns box kiri_atas">
        <div id="welcome" class="idle">
            <a href="<?php echo Yii::app()->baseUrl; ?>">
                <img src="<?php echo $logoSrc ?>" alt="logo" />
            </a>
            <h1>Selamat datang di <?php echo $namaToko ?></h1>
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
            <p><?php echo "{$user['namaLengkap']}" //[#{$user['id']}]"
                ?></p>
            <p>Selamat berbelanja di <?php echo $namaToko ?></p>
        </div>
        <div id="info_cust" class="proc">
            <img src="<?php echo $logoSrc ?>" alt="logo" />
            <p>Ahlan wa Sahlan!</p>
            <p>Silahkan input nomor</p>
            <p>member anda</p>
        </div>
        <div id="info_checkout" class="checkout">
            <img src="<?php echo $logoSrc ?>" alt="logo" />
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
            <?php
            $wSubuh   = substr($waktu['Fajr'] ?? '', 0, 5);
            $wSyuruq  = substr($waktu['Sunrise'] ?? '', 0, 5);
            $wZhuhur  = substr($waktu['Dhuhr'] ?? '', 0, 5);
            $wAshar   = substr($waktu['Asr'] ?? '', 0, 5);
            $wMaghrib = substr($waktu['Maghrib'] ?? '', 0, 5);
            $wIsya    = substr($waktu['Isha'] ?? '', 0, 5);
            if ($waktu != null) {
            ?>
                <p class="caption_selanjutnya"></p>
                <p class="waktu_selanjutnya"></p>
                <p class="sholat_selanjutnya"></p>
                <jadwal_sholat>
                    <p>Jadwal Sholat Hari Ini</p>
                    <span class="nama">Subuh / الفجر</span><span class="waktu"><?php echo $wSubuh ?></span>
                    <span class="nama">Syuruq / الشروق</span><span class="waktu"><?php echo $wSyuruq ?></span>
                    <span class="nama">Zuhur / الظُهر</span><span class="waktu"><?php echo $wZhuhur ?></span>
                    <span class="nama">'Ashar / العصر</span><span class="waktu"><?php echo $wAshar ?></span>
                    <span class="nama">Maghrib / المغرب</span><span class="waktu"><?php echo $wMaghrib ?></span>
                    <span class="nama">Isya' / العِشاء</span><span class="waktu"><?php echo $wIsya ?></span>
                </jadwal_sholat>
            <?php
            }
            ?>
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
    var brosur = <?php echo $brosurs ?>;
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
            $("#brosur-container>img").attr('src', brosur[curBrosur]);
            brosurIntervalID = setInterval(function() {
                $("#brosur-container>img").attr('src', brosur[curBrosur]);
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
            'Subuh': '<?php echo $wSubuh ?>',
            'Syuruq': '<?php echo $wSyuruq ?>',
            'Zhuhur': '<?php echo $wZhuhur ?>',
            'Ashar': '<?php echo $wAshar ?>',
            'Maghrib': '<?php echo $wMaghrib ?>',
            'Isya': '<?php echo $wIsya ?>'
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
            waktuSelanjutnya = '<?php echo $wSubuh ?>'
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
            console.log("Error: " + e)
            console.log("Pesan: " + pesan)
            // console.log("Parsed: " + parsed)
            // output.html('Kemungkinan Error: ' + e);
        }
    }

    function isValidUser(id) {
        return id == <?php echo $user['id'] ?>;
    }

    function parseMessage(data) {
        // console.log('User: ' + parsed.uId)
        if (data.tipe == "<?php echo AhadPosWsClient::TIPE_BROSUR_UPDATE ?>") {
            window.brosur = data.imgs;
            changeBrosur();
        } else
        if (data.tipe == "<?php echo AhadPosWsClient::TIPE_LOGO_UPDATE ?>") {
            location.reload(true);
        } else {
            var userId = data.uId;
            if (isValidUser(userId)) {
                // console.log('User accepted!');
                if (data.tipe == "<?php echo AhadPosWsClient::TIPE_WINDOW_REFRESH ?>") {
                    location.reload(true);
                }
                placeVar(data)
            }
        }
    }

    function placeVar(data) {
        if (data.tipe == "<?php echo AhadPosWsClient::TIPE_PROCESS ?>") {
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
        } else if (data.tipe == "<?php echo AhadPosWsClient::TIPE_IDLE ?>") {
            // console.log("Tipe Idle")
            // console.log('Qris dialog close');
            $('#qrcode-wrapper').foundation('reveal', 'close');
            $(".checkout").hide();
            $(".proc").fadeOut().promise().done(function() {
                $(".idle").fadeIn();
            });
        } else if (data.tipe == "<?php echo AhadPosWsClient::TIPE_CHECKOUT ?>") {
            // console.log("Tipe Checkout")
            $(".idle").hide();
            $(".proc").fadeOut().promise().done(function() {
                $(".checkout").fadeIn();
            });
            injectPayment(data)
        } else if (data.tipe == "<?php echo AhadPosWsClient::TIPE_QRIS_SHOW ?>") {

            // console.log('Qris dialog close');
            $('#qrcode-wrapper').foundation('reveal', 'close');
            // console.log('jumlah: ' + data.jumlah)
            setTimeout(function() {
                // console.log('Qris dialog open');
                $('#qrcode-wrapper').foundation('reveal', 'open');
                $("#qrcode-jml").html('<h1>' + data.jumlah + '</h1>');
                $("#ket").text("Scan QR untuk bayar");
                $("#qrcode").css('background', '#fff').empty();
                qr = new QRCode(document.getElementById("qrcode"), {
                    text: data.qrcode,
                    width: 300,
                    height: 300,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });

            }, 500)
            // Flag to indicate we're waiting for QRIS payment
            window.qrisWaiting = true;
        } else if (data.tipe == "<?php echo AhadPosWsClient::TIPE_QRIS_PAID ?>") {
            window.qrisWaiting = false;
            $("#qrcode").slideUp(1000, function() {
                $(this)
                    .css("background", "transparent")
                    .html('<i class="fa fa-check-circle check-animate" aria-hidden="true"></i>')
                    .slideDown();
                $("#ket").text("Pembayaran diterima");
            });
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
    const url = 'ws://<?php echo $ws['ip'] ?>:<?php echo $ws['port'] ?>';

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