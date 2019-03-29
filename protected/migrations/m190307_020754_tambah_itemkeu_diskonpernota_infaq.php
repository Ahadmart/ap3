<?php

class m190307_020754_tambah_itemkeu_diskonpernota_infaq extends CDbMigration
{

    public function safeUp()
    {
        $now  = date('Y-m-d H:i:s');
        $data = [
            [
                'id'         => 10,
                'nama'       => 'Infak/Sedekah via POS',
                'parent_id'  => 9,
                'jenis'      => 1,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
            [
                'id'         => 11,
                'nama'       => 'Diskon per Nota POS',
                'parent_id'  => 7,
                'jenis'      => 0,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ]
        ];

        $this->insertMultiple('item_keuangan', $data);
    }

    public function safeDown()
    {
        echo "m190307_020754_tambah_itemkeu_diskonpernota_infaq does not support migration down.\n";
        return false;
    }

}
