<?php
class Pecahan
{
    public int $pembilang;
    public int $penyebut;

    public function __construct(int $pembilang, int $penyebut)
    {
        if ($penyebut === 0) {
            throw new Exception('Penyebut tidak boleh 0');
        }

        $FPB = $this->fpb($pembilang, $penyebut);
        $pembilang /= $FPB;
        $penyebut /= $FPB;

        // Jika minus di penyebut, pindahkan ke pembilang
        if ($penyebut < 0) {
            $pembilang = -$pembilang;
            $penyebut  = -$penyebut;
        }

        $this->pembilang = $pembilang;
        $this->penyebut  = $penyebut;
    }

    /**
     * Method fpb 
     * Mencari Faktor Persekutuan Terbesar
     *
     * @param int $a pembilang
     * @param int $b penyebut
     *
     * @return int
     */
    public function fpb(int $a, int $b): int
    {
        return $b == 0 ? abs($a) : $this->fpb($b, $a % $b);
    }

    public function multiply(Pecahan $pecahan): Pecahan
    {
        return new Pecahan($this->pembilang * $pecahan->pembilang, $this->penyebut * $pecahan->penyebut);
    }

    public function toFloat(): float
    {
        return $this->pembilang / $this->penyebut;
    }

    public function toString(): string
    {
        return $this->penyebut == 1 ? (string) $this->pembilang : "{$this->pembilang}/{$this->penyebut}";
    }


    /**
     * Method buatPecahan
     *
     * @param string $input pecahan sederhana dalam string
     *
     * @return Pecahan
     */
    static function buatPecahan(string $input): Pecahan
    {
        if (strpos($input, '/') !== false) {
            [$pem, $peny] = explode('/', $input, 2);
            return new Pecahan((int) $pem, (int) $peny);
        } else {
            // jika bilangan bulat, penyebutnya 1
            return new Pecahan((int) $input, 1);
        }
    }
}
