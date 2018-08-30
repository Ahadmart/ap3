<?php

/**
 * ReportHutangPiutangForm class.
 * ReportHutangPiutangForm is the data structure for keeping
 * report hutangPiutang form data. It is used by the 'hutangpiutang' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportHutangPiutangForm extends CFormModel
{

    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $profilId;
    public $showDetail = true;
    public $kertas;
    public $pilihCetak = ['hutang'];

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['profilId, showDetail, kertas', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId' => 'Profil',
            'showDetail' => 'Tampilkan Detail',
        ];
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function reportHutangPiutang()
    {
        $pilihHutang = false;
        $pilihPiutang = false;
        if (is_array($this->pilihCetak)) {
            foreach ($this->pilihCetak as $pilihan) {
                if ($pilihan == 'hutang') {
                    $pilihHutang = true;
                } else if ($pilihan == 'piutang') {
                    $pilihPiutang = true;
                }
            }
        }

        $showDetail = false;
        if (is_array($this->showDetail)) {
            foreach ($this->showDetail as $show) {
                if ($show == true) {
                    $showDetail = true;
                }
            }
        }

        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select('tipe, sum(jumlah) jumlah,  sum(jumlah_bayar) jumlah_bayar');
        $commandRekap->from("hutang_piutang hp");
        $commandRekap->leftJoin("
                (SELECT
                    hutang_piutang_id, SUM(jumlah) jumlah_bayar
                FROM
                    (SELECT
                    detail.hutang_piutang_id, detail.jumlah
                FROM
                    penerimaan_detail detail
                JOIN hutang_piutang hp ON detail.hutang_piutang_id = hp.id
                    AND hp.profil_id = :profilId
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN penerimaan ON detail.penerimaan_id = penerimaan.id 
                    AND penerimaan.status= :statusPenerimaan
                UNION
                SELECT
                    detail.hutang_piutang_id, detail.jumlah
                FROM
                    pengeluaran_detail detail
                JOIN hutang_piutang hp ON detail.hutang_piutang_id = hp.id
                    AND hp.profil_id = :profilId
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN pengeluaran ON detail.pengeluaran_id = pengeluaran.id
                    AND pengeluaran.status = :statusPengeluaran
                    ) t
                GROUP BY hutang_piutang_id) tbayar", "hp.id = tbayar.hutang_piutang_id");
        $commandRekap->where("profil_id = :profilId  AND tipe = :tipeHp AND status = :statusHp");
        $commandRekap->group('tipe');

        $commandRekap->bindValues([
            ':profilId' => $this->profilId,
            ':tipeHp' => HutangPiutang::TIPE_HUTANG,
            ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
        ]);

        $rekapHutang = [];
        if ($pilihHutang) {
            $rekapHutang = $commandRekap->queryRow();
        }
        $commandRekap->bindValues([
            ':profilId' => $this->profilId,
            ':tipeHp' => HutangPiutang::TIPE_PIUTANG,
            ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
        ]);

        $rekapPiutang = [];
        if ($pilihPiutang) {
            $rekapPiutang = $commandRekap->queryRow();
        }

        $dataHutang = [];
        $dataPiutang = [];

        if ($showDetail) {
            $command = Yii::app()->db->createCommand();
            $command->select('hp.*, tbayar.*');
            $command->from("hutang_piutang hp");
            $command->leftJoin("
                (SELECT
                    hutang_piutang_id, SUM(jumlah) jumlah_bayar
                FROM
                    (SELECT
                    detail.hutang_piutang_id, detail.jumlah
                FROM
                    penerimaan_detail detail
                JOIN hutang_piutang hp ON detail.hutang_piutang_id = hp.id
                    AND hp.profil_id = :profilId
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN penerimaan ON detail.penerimaan_id = penerimaan.id 
                    AND penerimaan.status= :statusPenerimaan
                UNION
                SELECT
                    detail.hutang_piutang_id, detail.jumlah
                FROM
                    pengeluaran_detail detail
                JOIN hutang_piutang hp ON detail.hutang_piutang_id = hp.id
                    AND hp.profil_id = :profilId
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN pengeluaran ON detail.pengeluaran_id = pengeluaran.id
                    AND pengeluaran.status = :statusPengeluaran
                    ) t
                GROUP BY hutang_piutang_id) tbayar", "hp.id = tbayar.hutang_piutang_id");
            $command->order("nomor");
            $command->where("profil_id = :profilId  AND tipe = :tipeHp AND status = :statusHp");

            $command->bindValues([
                ':profilId' => $this->profilId,
                ':tipeHp' => HutangPiutang::TIPE_HUTANG,
                ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
            ]);

            if ($pilihHutang) {
                $dataHutang = $command->queryAll();
            }

            $command->bindValues([
                ':profilId' => $this->profilId,
                ':tipeHp' => HutangPiutang::TIPE_PIUTANG,
                ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
            ]);

            if ($pilihPiutang) {
                $dataPiutang = $command->queryAll();
            }
        }
        return [
            'rekapHutang' => $rekapHutang,
            'rekapPiutang' => $rekapPiutang,
            'dataHutang' => $dataHutang,
            'dataPiutang' => $dataPiutang
        ];
    }

    public function reportHutangPiutangCsv()
    {
        $csv = '"jenis","nomor_hp","tgl","asal_dokumen","asal_nomor","no_ref","jumlah","bayar","sisa"' . PHP_EOL;

        $pilihHutang = false;
        $pilihPiutang = false;
        if (is_array($this->pilihCetak)) {
            foreach ($this->pilihCetak as $pilihan) {
                if ($pilihan == 'hutang') {
                    $pilihHutang = true;
                } else if ($pilihan == 'piutang') {
                    $pilihPiutang = true;
                }
            }
        }

        $listAsalHP = HutangPiutang::model()->listNamaAsal();

        /* Untuk csv selalu tampilkan detail */
        $showDetail = true;

        if ($showDetail) {
            $command = Yii::app()->db->createCommand();
            $command->select('hp.*, tbayar.*');
            $command->from("hutang_piutang hp");
            $command->leftJoin("
                (SELECT
                    hutang_piutang_id, SUM(jumlah) jumlah_bayar
                FROM
                    (SELECT
                    detail.hutang_piutang_id, detail.jumlah
                FROM
                    penerimaan_detail detail
                JOIN hutang_piutang hp ON detail.hutang_piutang_id = hp.id
                    AND hp.profil_id = :profilId
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN penerimaan ON detail.penerimaan_id = penerimaan.id 
                    AND penerimaan.status= :statusPenerimaan
                UNION
                SELECT
                    detail.hutang_piutang_id, detail.jumlah
                FROM
                    pengeluaran_detail detail
                JOIN hutang_piutang hp ON detail.hutang_piutang_id = hp.id
                    AND hp.profil_id = :profilId
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN pengeluaran ON detail.pengeluaran_id = pengeluaran.id
                    AND pengeluaran.status = :statusPengeluaran
                    ) t
                GROUP BY hutang_piutang_id) tbayar", "hp.id = tbayar.hutang_piutang_id");
            $command->order("nomor");
            $command->where("profil_id = :profilId  AND tipe = :tipeHp AND status = :statusHp");

            $command->bindValues([
                ':profilId' => $this->profilId,
                ':tipeHp' => HutangPiutang::TIPE_HUTANG,
                ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
            ]);

            if ($pilihHutang) {
                $dataHutang = $command->queryAll();
                foreach ($dataHutang as $data) {
                    $sisa = $data['jumlah'] - $data['jumlah_bayar'];
                    if ($data['asal'] == HutangPiutang::DARI_PEMBELIAN) {
                        $dokAsal = Pembelian::model()->find("hutang_piutang_id={$data['id']}");
                        $ref = $dokAsal->referensi;
                    }
                    if ($data['asal'] == HutangPiutang::DARI_RETUR_JUAL) {
                        $dokAsal = ReturPenjualan::model()->find("hutang_piutang_id={$data['id']}");
                        $ref = $dokAsal->referensi;
                    }
                    $csv .=
                            "\"hutang\","
                            . "\"{$data['nomor']}\","
                            . "\"{$data['created_at']}\","
                            . "\"{$listAsalHP[$data['asal']]}\","
                            . "\"{$data['nomor_dokumen_asal']}\","
                            . "\"{$ref}\","
                            . "\"{$data['jumlah']}\","
                            . "\"{$data['jumlah_bayar']}\","
                            . "\"" . $sisa . "\","
                            . PHP_EOL;
                }
            }

            $command->bindValues([
                ':profilId' => $this->profilId,
                ':tipeHp' => HutangPiutang::TIPE_PIUTANG,
                ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
            ]);

            if ($pilihPiutang) {
                $dataPiutang = $command->queryAll();
                foreach ($dataPiutang as $data) {
                    $sisa = $data['jumlah'] - $data['jumlah_bayar'];
                    if ($data['asal'] == HutangPiutang::DARI_PENJUALAN) {
                        $dokAsal = Penjualan::model()->find("hutang_piutang_id={$data['id']}");
                        $ref = $dokAsal->referensi;
                    }
                    if ($data['asal'] == HutangPiutang::DARI_RETUR_BELI) {
                        $ref = '';
                    }
                    $csv .=
                            "\"piutang\","
                            . "\"{$data['nomor']}\","
                            . "\"{$data['created_at']}\","
                            . "\"{$listAsalHP[$data['asal']]}\","
                            . "\"{$data['nomor_dokumen_asal']}\","
                            . "\"{$ref}\","
                            . "\"{$data['jumlah']}\","
                            . "\"{$data['jumlah_bayar']}\","
                            . "\"" . $sisa . "\","
                            . PHP_EOL;
                }
            }
        }
        return $csv;
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA
        ];
    }

}
