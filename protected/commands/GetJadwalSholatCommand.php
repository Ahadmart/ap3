<?php

class GetJadwalSholatCommand extends CConsoleCommand
{
    public function actionIndex($periode, $lat, $long)
    {
        echo 'Periode: ' . $periode . PHP_EOL;
        echo 'Koordinat: ' . $lat . ', ' . $long . PHP_EOL;

        $tahun = date('Y');
        $bulan = date('n');
        $url   = "https://api.aladhan.com/v1/calendar/{$tahun}/{$bulan}";
        $param = [
            'latitude'  => -6.3940,
            'longitude' => 106.8225,
            'method'    => 20,
            'tune'      => '3,3,-3,3,3,3,3,3'
        ];
        file_put_contents(__DIR__ . "/../../assets/jadwalsholat_{$tahun}{$bulan}.json", $this->getRequest($url, $param));
    }

    private function getRequest($url, $param)
    {
        $ch = curl_init($url . '?' . http_build_query($param));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);

        return $r;
    }
}
