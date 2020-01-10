<?php

class m191226_025056_tambah_itemkeu_tariktunai extends CDbMigration
{

    public function safeUp()
    {

        $now  = date('Y-m-d H:i:s');
        $data = [
            [
                'id'         => 12,
                'nama'       => 'Tarik Tunai via POS',
                'parent_id'  => 8,
                'jenis'      => 0,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
            [
                'id'         => 13,
                'nama'       => 'Tarik Tunai (Penerimaan) via POS',
                'parent_id'  => 9,
                'jenis'      => 1,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
        ];

        $this->insertMultiple('item_keuangan', $data);
    }

    public function safeDown()
    {
        echo "m191226_025056_tambah_itemkeu_tariktunai does not support migration down.\n";
        return false;
    }

}
