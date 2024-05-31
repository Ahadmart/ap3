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
        $periode  = date('Yn');
        $dir      = __DIR__ . '/../../../assets/';
        $fileName = "jadwalsholat_{$periode}.json";
        $file     = $dir . $fileName;

        if (file_exists($file)) {
            // Nothing to do
        } else {
            // Hapus file-file yang mungkin ada di bulan sebelumnya
            array_map('unlink', glob($dir . 'jadwalsholat*.json'));

            // Ambil jadwal bulan berjalan ke internet
            $this->getJadwalSholat($periode, $latitude, $longitude, $offset, $file);
        }

        $fileContent   = file_get_contents($file);
        $jadwalSebulan = json_decode($fileContent, true, 512, JSON_UNESCAPED_UNICODE);

        $i = 0;
        foreach ($jadwalSebulan['data'] as $jadwal) {
            if ($jadwal['date']['gregorian']['date'] == date('d-m-Y')) {
                break;
            }
            $i++;
        }

        $this->render('desktop', [
            'namaToko' => $namaToko,
            'ws'       => $ws,
            'user'     => $user,
            'jadwal'   => $jadwalSebulan['data'][$i],
            'logo'     => $this->getLogo(),
            'brosurs'  => json_encode($this->getBrosurPromo()),

        ]);
    }

    private function getJadwalSholat($periode, $lat, $long, $offset, $file)
    {
        // echo 'Periode: ' . $periode . PHP_EOL;
        // echo 'Koordinat: ' . $lat . ', ' . $long . PHP_EOL;

        $tahun = substr($periode, 0, 4);
        $bulan = substr($periode, 4, 2);
        $url   = "https://api.aladhan.com/v1/calendar/{$tahun}/{$bulan}";
        $param = [
            'latitude'  => $lat,
            'longitude' => $long,
            'method'    => 20,
            'tune'      => $offset,
        ];
        file_put_contents($file, $this->getRequest($url, $param));
    }

    private function getRequest($url, $param)
    {
        $ch = curl_init($url . '?' . http_build_query($param));
        Yii::log("Ambil data dari {$url}?" . http_build_query($param));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);

        return $r;
    }

    private function getLogo()
    {
        require_once __DIR__ . '/BrosurpromoController.php';
        $assetPath = BrosurpromoController::ASSETS_PATH;
        // Only one
        $imgs      = [];
        foreach (glob($assetPath . 'logo*.*', GLOB_BRACE) as $filename) {
            $imgs[] =  $this->createUrl($filename);
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
            $imgs[] =  $this->createUrl($filename);
        }
        return $imgs;
    }
}
