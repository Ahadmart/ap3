<?php

/**
 * ReportRekapHutangPiutangForm class.
 * ReportRekapHutangPiutangForm is the data structure for keeping
 * report rekapHutangPiutang form data. It is used by the 'rekaphutangpiutang' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportRekapHutangPiutangForm extends CFormModel
{

    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $showDetail = false;
    public $kertas;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('showDetail, kertas', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'showDetail' => 'Tampilkan Detail',
        );
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function reportRekapHutangPiutang()
    {

        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select('sum(jumlah) jumlah,  sum(jumlah_bayar) jumlah_bayar');
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
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN pengeluaran ON detail.pengeluaran_id = pengeluaran.id
                    AND pengeluaran.status = :statusPengeluaran) t
                GROUP BY hutang_piutang_id) tbayar", "hp.id = tbayar.hutang_piutang_id");
        $commandRekap->where("tipe = :tipeHp AND status = :statusHp");

        $commandRekap->bindValues([
            ':tipeHp' => HutangPiutang::TIPE_HUTANG,
            ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
        ]);

        $rekapHutang = $commandRekap->queryRow();

        $commandRekap->bindValues([
            ':tipeHp' => HutangPiutang::TIPE_PIUTANG,
            ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
        ]);

        $rekapPiutang = $commandRekap->queryRow();



        $command = Yii::app()->db->createCommand();
        $command->select('profil_id, profil.nama, sum(jumlah) jumlah,  sum(jumlah_bayar) jumlah_bayar');
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
                    AND hp.status = :statusHp
                    AND hp.tipe = :tipeHp
                JOIN pengeluaran ON detail.pengeluaran_id = pengeluaran.id
                    AND pengeluaran.status = :statusPengeluaran) t
                GROUP BY hutang_piutang_id) tbayar", "hp.id = tbayar.hutang_piutang_id");
        $command->join("profil", "hp.profil_id = profil.id");
        $command->where("tipe = :tipeHp AND status = :statusHp");
        $command->group('profil_id');
        $command->order("profil.nama");

        $command->bindValues([
            ':tipeHp' => HutangPiutang::TIPE_HUTANG,
            ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
        ]);

        $dataHutang = $command->queryAll();

        $command->bindValues([
            ':tipeHp' => HutangPiutang::TIPE_PIUTANG,
            ':statusHp' => HutangPiutang::STATUS_BELUM_LUNAS,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR
        ]);

        $dataPiutang = $command->queryAll();

        return [
            'rekapHutang' => $rekapHutang,
            'rekapPiutang' => $rekapPiutang,
            'dataHutang' => $dataHutang,
            'dataPiutang' => $dataPiutang,
        ];
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
