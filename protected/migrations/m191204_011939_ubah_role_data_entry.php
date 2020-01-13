<?php

class m191204_011939_ubah_role_data_entry extends CDbMigration
{

    public function safeUp()
    {
        $this->delete('AuthItemChild', "parent=:parent AND child=:child", [
            ':parent' => 'DATA_ENTRY',
            ':child'  => 'transaksiPenjualan'
        ]);
        $this->delete('AuthItemChild', "parent=:parent AND child=:child", [
            ':parent' => 'DATA_ENTRY',
            ':child'  => 'transaksiReturPembelian'
        ]);
        $this->delete('AuthItemChild', "parent=:parent AND child=:child", [
            ':parent' => 'DATA_ENTRY',
            ':child'  => 'transaksiSO'
        ]);

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'DATA_ENTRY', ':child' => 'transaksiPenjualan-simpan'],
            [':parent' => 'DATA_ENTRY', ':child' => 'transaksiReturPembelian-simpan'],
            [':parent' => 'DATA_ENTRY', ':child' => 'transaksiSO-simpan'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m191204_011939_ubah_role_data_entry does not support migration down.\n";
        return false;
    }

}
