<?php
class BFormatter extends CFormatter
{
    public function formatPpnFaktur($value, $pattern = '___.___.___._-___.___')
    {
        $chPattern =  str_split($pattern);
        $length = strlen($pattern);
        $r = '';
        $i = 0;
        foreach ($chPattern as $ch) {
            if ($ch == '_') {
                $r .= $value[$i];
                $i++;
            } else {
                $r .= $ch;
            }
        }
        return $r;
    }
}
