<?php

/**
 * ReportPengeluaranPenerimaanForm class.
 * ReportPengeluaranPenerimaanForm is the data structure for keeping
 * report penjualan form data. It is used by the 'pengeluaranpenerimaan' action of 'ReportController'.
 * 
 */
class ReportPengeluaranPenerimaanForm extends CFormModel
{

    public $profilId;
    public $itemKeuId;
    public $dari;
    public $sampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('profilId, itemKeuId', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'profilId' => 'Profil',
            'itemKeuId' => 'Item Keuangan',
            'dari' => 'Dari',
            'sampai' => 'Sampai'
        );
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function getNamaItemKeu()
    {
        $itemKeu = ItemKeuangan::model()->findByPk($this->itemKeuId);
        $namaParent = isset($itemKeu->parent) ? '(' . $itemKeu->parent->nama . ')' : '(-)';
        return $namaParent . ' ' . $itemKeu->nama;
    }

    public function reportPengeluaranPenerimaan()
    {
        $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');

        $queryItem = '';
        $selectProfil = '';
        $queryProfil = '';
        $joinProfil = '';
        if (!empty($this->itemKeuId)) {
            $queryItem = 'AND item.id = :itemId';
        }
        if (!empty($this->profilId)) {
            $selectProfil = 'profil.nama profil,';
            $queryProfil = 'AND profil.id = :profilId';
        }

        $command = Yii::app()->db->createCommand();
        $command->from("
        (
        SELECT 
            0 jenis_nota,
            p.nomor,
            p.tanggal,
            profil.nama profil,
            kategori.nama kategori,
            jenis.nama jenis_tr,
            p.keterangan nota_ket,
            detail.item_id,
            item.nama item,
            item.jenis item_jenis,
            detail.keterangan,
            detail.jumlah,
            detail.posisi
        FROM
            pengeluaran_detail detail
                JOIN
            item_keuangan item ON detail.item_id = item.id
                {$queryItem}
                JOIN
            pengeluaran p ON detail.pengeluaran_id = p.id
                AND p.status = :statusPengeluaran
                AND DATE_FORMAT(p.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
                JOIN
           profil ON p.profil_id = profil.id
                {$queryProfil}
                JOIN
            pengeluaran_kategori kategori ON p.kategori_id = kategori.id
                JOIN
            jenis_transaksi jenis ON p.jenis_transaksi_id = jenis.id 
        UNION SELECT 
            1 jenis_nota,
            p.nomor,
            p.tanggal,
            profil.nama profil,
            kategori.nama kategori,
            jenis.nama jenis_tr,
            p.keterangan nota_ket,
            detail.item_id,
            item.nama item,
            item.jenis item_jenis,
            detail.keterangan,
            detail.jumlah,
            detail.posisi
        FROM
            penerimaan_detail detail
                JOIN
            item_keuangan item ON detail.item_id = item.id
                {$queryItem}
                JOIN
            penerimaan p ON detail.penerimaan_id = p.id
                AND p.status = :statusPenerimaan
                AND DATE_FORMAT(p.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
                JOIN
            profil ON p.profil_id = profil.id
                {$queryProfil}
                JOIN
            penerimaan_kategori kategori ON p.kategori_id = kategori.id
                JOIN
            jenis_transaksi jenis ON p.jenis_transaksi_id = jenis.id
        ) AS t
                ");
        $command->order("tanggal, nomor");

        if (!empty($this->itemKeuId)) {
            $command->bindValue(':itemId', $this->itemKeuId);
        }
        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        $command->bindValue(':statusPengeluaran', Pengeluaran::STATUS_BAYAR);
        $command->bindValue(':statusPenerimaan', Penerimaan::STATUS_BAYAR);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        $data = $command->queryAll();

        return ['detail' => $data];
    }

}
