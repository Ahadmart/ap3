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

        $queryItem = 'AND item.id >' . ItemKeuangan::ITEM_TRX_SAJA;
        $selectProfil = '';
        $queryProfil = '';
        if (!empty($this->itemKeuId)) {
            $itemK = ItemKeuangan::model()->findByPk($this->itemKeuId);
            if (empty($itemK->parent_id)) {
                /* Parent ID = null, berarti punya anak. 
                 * Cari dan masukkan ke kondisi query
                 */
                $queryItem = 'AND item.id IN (';
                $itemsK    = ItemKeuangan::model()->findAll('parent_id = :parentId', [':parentId' => $this->itemKeuId]);
                $first     = true;
                foreach ($itemsK as $item) {
                    if (!$first) {
                        $queryItem .= ',';
                    }
                    $queryItem .= $item->id;
                    $first     = false;
                }
                $queryItem .= ')';
            } else {
                /* Punya parent, berarti item transaksi */
                $queryItem = 'AND item.id = :itemId';
            }
        }
        if (!empty($this->profilId)) {
            $selectProfil = 'profil.nama profil,';
            $queryProfil = 'AND profil.id = :profilId';
        }
        
        $sqlForm = "(
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
            CASE posisi
                WHEN 0 THEN detail.jumlah
                ELSE NULL
            END debet,
            CASE posisi
                WHEN 1 THEN detail.jumlah
                ELSE NULL
            END kredit,
            detail.posisi
        FROM
            pengeluaran_detail detail
                JOIN
            item_keuangan item ON detail.item_id = item.id
                {$queryItem}
                JOIN
            pengeluaran p ON detail.pengeluaran_id = p.id
                AND p.status = :statusPengeluaran
                AND p.tanggal BETWEEN :dari AND :sampai
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
            CASE posisi
                WHEN 1 THEN detail.jumlah
                ELSE NULL
            END debet,
            CASE posisi
                WHEN 0 THEN detail.jumlah
                ELSE NULL
            END kredit,
            detail.posisi
        FROM
            penerimaan_detail detail
                JOIN
            item_keuangan item ON detail.item_id = item.id
                {$queryItem}
                JOIN
            penerimaan p ON detail.penerimaan_id = p.id
                AND p.status = :statusPenerimaan
                AND p.tanggal BETWEEN :dari AND :sampai
                JOIN
            profil ON p.profil_id = profil.id
                {$queryProfil}
                JOIN
            penerimaan_kategori kategori ON p.kategori_id = kategori.id
                JOIN
            jenis_transaksi jenis ON p.jenis_transaksi_id = jenis.id
        ) AS t";

        $command = Yii::app()->db->createCommand();
        $command->from($sqlForm);
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

        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select("sum(debet) total_debet, sum(kredit) total_kredit");
        $commandRekap->from($sqlForm);
        
        if (!empty($this->itemKeuId)) {
            $commandRekap->bindValue(':itemId', $this->itemKeuId);
        }
        if (!empty($this->profilId)) {
            $commandRekap->bindValue(':profilId', $this->profilId);
        }
        $commandRekap->bindValue(':statusPengeluaran', Pengeluaran::STATUS_BAYAR);
        $commandRekap->bindValue(':statusPenerimaan', Penerimaan::STATUS_BAYAR);
        $commandRekap->bindValue(':dari', $dari);
        $commandRekap->bindValue(':sampai', $sampai);
        
        $rekap = $commandRekap->queryRow();

        return ['detail' => $data, 'rekap' => $rekap];
    }

    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    /**
     * Export Laporan Pengeluaran Penerimaan (Detail) ke CSV
     * @return text csv beserta header
     */
    public function toCsv()
    {
        $hasil  = $this->reportPengeluaranPenerimaan();
        $report = [];
        $report = $hasil['detail'];

        return $this->array2csv($report);
    }
}
