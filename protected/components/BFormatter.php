<?php
class BFormatter extends CFormatter
{
    public function formatPpnFaktur($value, $pattern = '___.___.___._-___.___')
    {
        $r = '';
        if (!empty($value)) {
            $chPattern =  str_split($pattern);
            $r         = '';
            $i         = 0;
            foreach ($chPattern as $ch) {
                if ($ch == '_') {
                    $r .= $value[$i];
                    $i++;
                } else {
                    $r .= $ch;
                }
            }
        }
        return $r;
    }

    public function formatTanggalWaktu($value, $format = 'd-m-Y H:i:s')
    {
        return date($format, strtotime($value));
    }

    public function formatUang($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function formatNomorDokumen($value)
    {
        $kodeCabang = substr($value, 0, 2);
        $kodeDokumen = substr($value, 2, 2);
        $tahunBulan = substr($value, 4, 4);
        $urutan = substr($value, -6);
        return $kodeCabang . ' ' . $kodeDokumen . ' ' . $tahunBulan . ' ' . $urutan;
    }
}
