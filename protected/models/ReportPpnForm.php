<?php

/**
 * ReportPpnForm
 * is the data structure for keeping report ppn data.
 * It used by the 'ppn' action of 'ReportController'.
 */
class ReportPpnForm extends CFormModel
{
    public string $periode; // date Ym
    public bool $detailPpnPembelianValid   = true;
    public bool $detailPpnPembelianPending = true;

    // Variabel untuk convert periode dalam range Y-m-d H:i:s
    // (Agar tetap menggunakan index)
    public DateTime $tanggalAwal;
    public DateTime $tanggalAkhir;

    /**
     * Declares the validation rules.     *
     */
    public function rules()
    {
        return [
            ['periode', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['detailPpnPembelianValid, detailPpnPembelianPending', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'periode'                   => 'Periode',
            'detailPpnPembelianValid'   => 'Detail Ppn Pembelian Valid',
            'detailPpnPembelianPending' => 'Detail Ppn Pembelian Pending',
        ];
    }

    public function beforeValidate()
    {
        $this->detailPpnPembelianPending = $this->detailPpnPembelianPending == 1 ? true : false;
        $this->detailPpnPembelianValid   = $this->detailPpnPembelianValid == 1 ? true : false;
        return parent::beforeValidate();
    }

    public function reportPpn()
    {
        $this->tanggalAwal  = DateTime::createFromFormat('Y-m-d H:i:s', $this->periode . '-01 00:00:00');
        $this->tanggalAkhir = DateTime::createFromFormat('Y-m-d H:i:s', $this->periode . '-01 00:00:00');
        $this->tanggalAkhir->modify('+1 month');
        $r = [
            'totalPpnPembelianPending' => $this->totalPpnPembelianPending(),
            'totalPpnPembelianValid'   => $this->totalPpnPembelianValid(),
            'totalPpnPenjualan'        => $this->totalPpnPenjualan(),
        ];
        if ($this->detailPpnPembelianPending) {
            $r['detailPpnPembelianPending'] = $this->detailPpnPembelianPending();
        }
        if ($this->detailPpnPembelianValid) {
            $r['detailPpnPembelianValid'] = $this->detailPpnPembelianValid();
        }
        return [
            'sukses' => true,
            'data'   => $r
        ];
    }

    public function totalPpnPembelianPending()
    {
        $sql = '
        SELECT
            IFNULL(SUM(total_ppn_hitung), 0) total
        FROM
            pembelian_ppn
        WHERE
            status = :statusPending
                AND updated_at >= :tglAwal
                AND updated_at < :tglAkhir
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusPending' => PembelianPpn::STATUS_PENDING,
            ':tglAwal'       => $this->tanggalAwal->format('Y-m-d H:i:s'),
            ':tglAkhir'      => $this->tanggalAkhir->format('Y-m-d H:i:s'),
        ]);

        if ($command->queryRow() == false) {
            return 0;
        }
        $r = $command->queryRow();
        return $r['total'];
    }

    public function totalPpnPembelianValid()
    {
        $sql = '
        SELECT
            IFNULL(SUM(total_ppn_faktur), 0) total
        FROM
            pembelian_ppn
        WHERE
            status = :statusValid
                AND updated_at >= :tglAwal
                AND updated_at < :tglAkhir
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusValid' => PembelianPpn::STATUS_VALID,
            ':tglAwal'     => $this->tanggalAwal->format('Y-m-d H:i:s'),
            ':tglAkhir'    => $this->tanggalAkhir->format('Y-m-d H:i:s'),
        ]);

        if ($command->queryRow() == false) {
            return 0;
        }
        $r = $command->queryRow();
        return $r['total'];
    }

    public function detailPpnPembelianPending()
    {
        $sql = '
        SELECT
            profil.nama,
            no_faktur_pajak,
            p.nomor,
            total_ppn_hitung AS jumlah
        FROM
            pembelian_ppn t
                JOIN
            pembelian p ON p.id = t.pembelian_id
                JOIN
            profil ON profil.id = p.profil_id
        WHERE
            t.status = :statusPending
                AND t.updated_at >= :tglAwal
                AND t.updated_at < :tglAkhir
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusPending' => PembelianPpn::STATUS_PENDING,
            ':tglAwal'       => $this->tanggalAwal->format('Y-m-d H:i:s'),
            ':tglAkhir'      => $this->tanggalAkhir->format('Y-m-d H:i:s'),
        ]);

        return $command->queryAll();
    }

    public function detailPpnPembelianValid()
    {
        $sql = '
        SELECT
            profil.nama,
            no_faktur_pajak,
            p.nomor,
            total_ppn_faktur AS jumlah
        FROM
            pembelian_ppn t
                JOIN
            pembelian p ON p.id = t.pembelian_id
                JOIN
            profil ON profil.id = p.profil_id
        WHERE
            t.status = :statusValid
                AND t.updated_at >= :tglAwal
                AND t.updated_at < :tglAkhir
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusValid' => PembelianPpn::STATUS_VALID,
            ':tglAwal'     => $this->tanggalAwal->format('Y-m-d H:i:s'),
            ':tglAkhir'    => $this->tanggalAkhir->format('Y-m-d H:i:s'),
        ]);

        return $command->queryAll();
    }

    public function totalPpnPenjualan()
    {
        $sql = '
        SELECT
            SUM(qty * ppn) total
        FROM
            penjualan_detail t
                JOIN
            penjualan p ON p.id = t.penjualan_id
        WHERE
            p.tanggal >= :tglAwal
                AND p.tanggal < :tglAkhir
        GROUP BY penjualan_id
        HAVING SUM(qty * ppn) > 0
        ';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues([
            ':tglAwal'  => $this->tanggalAwal->format('Y-m-d H:i:s'),
            ':tglAkhir' => $this->tanggalAkhir->format('Y-m-d H:i:s'),

        ]);

        if ($command->queryRow() == false) {
            return 0;
        }
        $r = $command->queryRow();
        return $r['total'];
    }
}
