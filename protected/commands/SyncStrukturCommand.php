<?php

class SyncStrukturCommand extends CConsoleCommand
{
    public $dbDC = 'dc_struktur';

    public function actionIndex()
    {
        echo "Generate data\n";
        $sql = "
        SELECT
            b.barcode,
            b.nama,
            b.struktur_id,
            bs3.nama bs3,
            bs2.nama bs2,
            bs1.nama bs1,
            t_dc.*
        FROM
            barang b
                LEFT JOIN
            barang_struktur bs3 ON bs3.id = b.struktur_id
                LEFT JOIN
            barang_struktur bs2 ON bs2.id = bs3.parent_id
                LEFT JOIN
            barang_struktur bs1 ON bs1.id = bs2.parent_id
                JOIN
            (SELECT
                db.barcode dc_barcode,
                    db.nama dc_nama,
                    db.struktur_id dc_struktur_id,
                    ds3.nama s3,
                    ds2.nama s2,
                    ds1.nama s1
            FROM
                {$this->dbDC}.barang db
            JOIN {$this->dbDC}.barang_struktur ds3 ON ds3.id = db.struktur_id
                AND TRIM(ds3.nama) != ''
            JOIN {$this->dbDC}.barang_struktur ds2 ON ds2.id = ds3.parent_id
            JOIN {$this->dbDC}.barang_struktur ds1 ON ds1.id = ds2.parent_id
            WHERE
                db.status = 1) t_dc ON t_dc.dc_barcode = b.barcode
        ";
        $command = Yii::app()->db->createCommand($sql);
        $r       = $command->queryAll();
        echo "Data generated\n";
        $i = 1;
        foreach ($r as $data) {
            $nomor = substr('000000' . $i, -7);
            echo '[' . $nomor . '] Sync barang ' . $data['barcode'] . "\n";
            $this->_samakanNama($data);
            $this->_samakanStruktur($data);
            $i++;
        }
    }

    private function _samakanNama($data)
    {
        echo "          Nama disinkronkan\n";
        $sql = "
        UPDATE barang
        SET
            nama = :namaBarang
        WHERE
            barcode = :barcode
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':namaBarang' => $data['dc_nama'],
            ':barcode'    => $data['barcode'],
        ]);
        $command->execute();
    }

    private function _samakanStruktur($data)
    {
        echo "          Struktur disinkronkan ";
        $sql = "
        SELECT 
            id, parent_id
        FROM
            barang_struktur
        WHERE
            nama = :namaStruk AND `level` = :level            
        ";
        $strukLv1Ada = Yii::app()->db->createCommand($sql)->bindValues([':namaStruk' => $data['s1'], ':level' => 1])->queryRow();
        if (empty($strukLv1Ada)) {
            $strukLv1Baru        = new StrukturBarang();
            $strukLv1Baru->nama  = $data['s1'];
            $strukLv1Baru->level = 1;
            if (!$strukLv1Baru->save()) {
                throw new Exception("Gagal simpan struktur (lv1) baru", 500);
            }
            $strukLv1Id = $strukLv1Baru->id;
        } else {
            $strukLv1Id = $strukLv1Ada['id'];
        }

        $sql = "
        SELECT 
            lv2.id, lv2.parent_id
        FROM
            barang_struktur lv2
                JOIN
            barang_struktur lv1 ON lv1.id = lv2.parent_id
        WHERE
            lv2.nama = :namaStruk AND lv2.`level` = :level
                AND lv1.nama = :namaLevel1         
        ";
        $strukLv2Ada = Yii::app()->db->createCommand($sql)->bindValues([':namaStruk' => $data['s2'], ':level' => 2, ':namaLevel1' => $data['s1']])->queryRow();
        if (empty($strukLv2Ada)) {
            $strukLv2Baru            = new StrukturBarang();
            $strukLv2Baru->nama      = $data['s2'];
            $strukLv2Baru->level     = 2;
            $strukLv2Baru->parent_id = $strukLv1Id;
            if (!$strukLv2Baru->save()) {
                throw new Exception("Gagal simpan struktur (lv2) baru", 500);
            }
            $strukLv2Id = $strukLv2Baru->id;
        } else {
            // Update parent nya, jika ada perubahan maka ikuti data DC
            Yii::app()->db->createCommand("UPDATE barang_struktur SET parent_id = {$strukLv2Ada['parent_id']} WHERE id = {$strukLv2Ada['id']}")->execute();
            $strukLv2Id = $strukLv2Ada['id'];
        }

        $sql = "
        SELECT 
            lv3.id, lv3.parent_id
        FROM
            barang_struktur lv3
                JOIN
            barang_struktur lv2 ON lv2.id = lv3.parent_id
                JOIN
            barang_struktur lv1 ON lv1.id = lv2.parent_id
        WHERE
            lv3.nama = :namaStruk AND lv3.`level` = :level
                AND lv2.nama = :namaLevel2
                AND lv1.nama = :namaLevel1        
        ";
        $strukLv3Ada = Yii::app()->db->createCommand($sql)->bindValues([':namaStruk' => $data['s3'], ':level' => 3, ':namaLevel2' => $data['s2'], ':namaLevel1' => $data['s1']])->queryRow();
        if (empty($strukLv3Ada)) {
            $strukLv3Baru            = new StrukturBarang();
            $strukLv3Baru->nama      = $data['s3'];
            $strukLv3Baru->level     = 3;
            $strukLv3Baru->parent_id = $strukLv2Id;
            if (!$strukLv3Baru->save()) {
                throw new Exception("Gagal simpan struktur (lv3) baru", 500);
            }
            $strukLv3Id = $strukLv3Baru->id;
        } else {
            // Update parent nya, jika ada perubahan maka ikuti data DC
            Yii::app()->db->createCommand("UPDATE barang_struktur SET parent_id = {$strukLv3Ada['parent_id']} WHERE id = {$strukLv3Ada['id']}")->execute();
            $strukLv3Id = $strukLv3Ada['id'];
        }

        $sql = "
        UPDATE barang
        SET
            struktur_id = :strukturId
        WHERE
            barcode = :barcode
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':strukturId' => $strukLv3Id,
            ':barcode'    => $data['barcode'],
        ]);
        $command->execute();
        echo "ke struktur_id: " . $strukLv3Id . "\n";
    }
}
