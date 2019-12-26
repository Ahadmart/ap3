<?php

class m191226_025056_tambah_itemkeu_tariktunai extends CDbMigration
{

    public function safeUp()
    {
        
        $now  = date('Y-m-d H:i:s');
        $data = [
                'id'         => 12,
                'nama'       => 'Tarik Tunai via POS',
                'parent_id'  => 8,
                'jenis'      => 0,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,            
        ];

        $this->insert('item_keuangan', $data);
    }

    public function safeDown()
    {
        echo "m191226_025056_tambah_itemkeu_tariktunai does not support migration down.\n";
        return false;
    }

}
