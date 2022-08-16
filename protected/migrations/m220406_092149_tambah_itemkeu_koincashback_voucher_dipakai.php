<?php

class m220406_092149_tambah_itemkeu_koincashback_voucher_dipakai extends CDbMigration
{

    public function safeUp()
    {
        $now  = date('Y-m-d H:i:s');
        $data = [
            [
                'id'         => 14,
                'nama'       => 'Koin Cashback Membership',
                'parent_id'  => 7,
                'jenis'      => 0,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
            [
                'id'         => 15,
                'nama'       => 'Voucher Membership',
                'parent_id'  => 7,
                'jenis'      => 0,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
        ];

        $this->insertMultiple('item_keuangan', $data);
    }

    public function safeDown()
    {
        echo "m220406_092149_tambah_itemkeu_koincashback_voucher_dipakai does not support migration down.\n";
        return false;
    }
}
