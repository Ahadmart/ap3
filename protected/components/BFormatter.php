<?php
class BFormatter extends CFormatter
{
    public function formatByPattern($value, $pattern)
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

    public function formatPpnFaktur($value, $pattern = '___.___-__.________')
    {
        return $this->formatByPattern($value, $pattern);
    }

    public function formatNpwp($value, $pattern = '___.___.___._-___.___')
    {
        return $this->formatByPattern($value, $pattern);
    }

    public function formatTanggalWaktu($value, $format = 'd-m-Y H:i:s')
    {
        return date($format, strtotime($value));
    }

    public function formatUang($value)
    {
        if (!empty($value)) {
            return number_format($value, 2, ',', '.');
        }
        return '';
    }

    public function formatNomorDokumen($value)
    {
        $kodeCabang  = substr($value, 0, 2);
        $kodeDokumen = substr($value, 2, 2);
        $tahunBulan  = substr($value, 4, 4);
        $urutan      = substr($value, -6);
        return $kodeCabang . ' ' . $kodeDokumen . ' ' . $tahunBulan . ' ' . $urutan;
    }
}
