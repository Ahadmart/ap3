<?php

class CustomerdisplayController extends Controller
{
    public $layout = '//layouts/nonavbar';

    public function actionMobile()
    {
        $this->render('mobile');
    }

    public function getInfoScan()
    {
        $criteria            = new CDbCriteria;
        $criteria->alias     = 'detail';
        $criteria->join      = 'JOIN penjualan pj on detail.penjualan_id = pj.id and pj.status=' . Penjualan::STATUS_DRAFT;
        $criteria->order     = 'detail.id desc';
        $criteria->condition = 'detail.updated_by =' . Yii::app()->user->id . ' AND TIMESTAMPDIFF(MINUTE, detail.updated_at, NOW()) <= 2'; //detail.updated_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        return PenjualanDetail::model()->find($criteria);
    }

    public function getInfoStruk()
    {
        return Penjualan::model()->find(['order' => 'id desc', 'condition' => 'status=' . Penjualan::STATUS_LUNAS . ' and TIMESTAMPDIFF(SECOND, tanggal, NOW()) <= 15']);
    }

    public function getInfoToko()
    {
        $config = Config::model()->find('nama=:namaToko', [':namaToko' => 'toko.nama']);
        return $config->nilai;
    }

    public function actionGetInfo()
    {
        if (!is_null($this->getInfoStruk())) {
            $this->renderPartial('_infostrukterakhir', ['penjualan' => $this->getInfoStruk()]);
        } elseif (!is_null($this->getInfoScan())) {
            $this->renderPartial('_infoscanterakhir', ['detailModel' => $this->getInfoScan()]);
        } else {
            $this->renderPartial('_kosong', ['namaToko' => $this->getInfoToko()]);
        }
    }

    public function actionDesktop()
    {
        $configCD = Config::model()->find('nama=:nama', [':nama' => 'customerdisplay.wsport']);
        $wsPort   = $configCD->nilai;
        $ws       = [
            'ip'   => $_SERVER['SERVER_ADDR'],
            'port' => $wsPort,
        ];
        $user = [
            'id'          => Yii::app()->user->id,
            'namaLengkap' => Yii::app()->user->namaLengkap,
        ];
        $config        = Config::model()->find('nama=:nama', [':nama' => 'toko.nama']);
        $koordinatConf = Config::model()->find('nama=:nama', [':nama' => 'jadwalsholat.koordinat']);
        $offsetConf    = Config::model()->find('nama=:nama', [':nama' => 'jadwalsholat.offset']);
        $koordinat     = explode(';', $koordinatConf->nilai);
        $namaToko      = $config->nilai;
        $latitude      = trim($koordinat[0]);
        $longitude     = trim($koordinat[1]);
        $offset        = $offsetConf->nilai;

        /* Cek file jadwal sholat untuk bulan berjalan
        Jika tidak ada, maka coba hapus file dengan pola sama
        kemudian coba download dari internet.
        Jika tidak berhasil tidak ditampilkan
         */
        // $tahun    = date('Y');
        // $bulan    = date('n');
        // $periode  = date('Yn');

        /* Cek file jadwal sholat untuk tahun berjalan
        Jika tidak ada, coba download dari internet.
        Jika tidak berhasil tidak ditampilkan
         */
        $periode  = date('Y');
        $dir      = __DIR__ . '/../../../assets/';
        $fileName = "jadwalsholat_{$periode}.json";
        $file     = $dir . $fileName;
        $waktu = null;
        if (!file_exists($file)) {
            // Hapus file-file yang mungkin ada di periode sebelumnya
            array_map('unlink', glob($dir . 'jadwalsholat*.json'));

            // Ambil jadwal tahun berjalan ke internet
            $this->getJadwalSholat($periode, $latitude, $longitude, $offset, $file);
        }

        if (file_exists($file)){
            $fileContent = file_get_contents($file);
            $jadwalSetahun = json_decode($fileContent, true, 512, JSON_UNESCAPED_UNICODE);

            $bulan = date('n');
            foreach ($jadwalSetahun['data'][$bulan] as $jadwal) {
                if ($jadwal['date']['gregorian']['date'] == date('d-m-Y')) {
                    $waktu = $jadwal['timings'];
                    break;
                }
            }
        }


        $this->render('desktop', [
            'namaToko' => $namaToko,
            'ws'       => $ws,
            'user'     => $user,
            // 'jadwal'   => $jadwalSetahun['data'][$i],
            'waktu'    => $waktu,
            'logo'     => $this->getLogo(),
            'brosurs'  => json_encode($this->getBrosurPromo()),

        ]);
    }

    private function getJadwalSholat($periode, $lat, $long, $offset, $file)
    {
        // echo 'Periode: ' . $periode . PHP_EOL;
        // echo 'Koordinat: ' . $lat . ', ' . $long . PHP_EOL;

        // $tahun = substr($periode, 0, 4);
        $tahun = $periode;
        // $bulan = substr($periode, 4, 2);
        // $url   = "https://api.aladhan.com/v1/calendar/{$tahun}/{$bulan}";
        $url   = "https://api.aladhan.com/v1/calendar/{$tahun}";
        $param = [
            'latitude'  => $lat,
            'longitude' => $long,
            'method'    => 20,
            'tune'      => $offset,
        ];
        if ($this->getRequest($url, $param) != null) {
            file_put_contents($file, $this->getRequest($url, $param));
            return true;
        }
        return false;
    }

    private function getRequest($url, $param)
    {
        $ch = curl_init($url . '?' . http_build_query($param));
        Yii::log("Ambil data dari {$url}?" . http_build_query($param));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        if ($r === false) {
            // Curl itself failed (DNS, timeout, SSL, etc.)
            $err   = curl_error($ch);
            $errno = curl_errno($ch);
            Yii::log("cURL error ({$errno}): {$err}", CLogger::LEVEL_ERROR);
            $r = null;
        } else {
            // Check HTTP status
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode !== 200) {
                Yii::log("HTTP error code: {$httpCode}", CLogger::LEVEL_ERROR);
                $r = null;
            } elseif (trim($r) === '') {
                // Got empty response
                Yii::log("Empty response from {$url}", CLogger::LEVEL_WARNING);
                $r = null;
            }
        }
        curl_close($ch);

        return $r;
    }

    private function getLogo()
    {
        require_once __DIR__ . '/BrosurpromoController.php';
        $assetPath = BrosurpromoController::ASSETS_PATH;
        // Only one
        $imgs = [];
        foreach (glob($assetPath . 'logo*.*', GLOB_BRACE) as $filename) {
            $imgs[] = $this->createUrl($filename);
        }
        if (isset($imgs[0])) {
            if (file_exists(realpath($filename))) {
                return $imgs[0];
            }
        }
        return '';
    }

    private function getBrosurPromo()
    {
        require_once __DIR__ . '/BrosurpromoController.php';
        $assetPath = BrosurpromoController::ASSETS_PATH;
        $imgs      = [];
        foreach (glob($assetPath . 'brosur*.*', GLOB_BRACE) as $filename) {
            $imgs[] = $this->createUrl($filename);
        }
        return $imgs;
    }
}
