<?php

class m191204_090314_ubah_role_kepala_toko_to_supervisor extends CDbMigration
{

    public function safeUp()
    {
        $this->update('AuthItem', [
            'name'        => 'SUPERVISOR',
            'description' => 'PENANGGUNG JAWAB'
                ], "name='KEPALA_TOKO'"
        );
        $this->update('AuthItemChild', [
            'parent' => 'SUPERVISOR',
                ], "parent='KEPALA_TOKO'"
        );

        $this->delete('AuthItemChild', "parent=:parent AND child=:child", [
            ':parent' => 'SUPERVISOR',
            ':child'  => 'transaksiPenjualan-simpan'
        ]);
        $this->delete('AuthItemChild', "parent=:parent AND child=:child", [
            ':parent' => 'SUPERVISOR',
            ':child'  => 'transaksiReturPembelian-simpan'
        ]);
        $this->delete('AuthItemChild', "parent=:parent AND child=:child", [
            ':parent' => 'SUPERVISOR',
            ':child'  => 'transaksiSO-simpan'
        ]);

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'SUPERVISOR', ':child' => 'transaksiPenjualan'],
            [':parent' => 'SUPERVISOR', ':child' => 'transaksiReturPembelian'],
            [':parent' => 'SUPERVISOR', ':child' => 'transaksiSO'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $this->update('AuthAssignment', [
            'itemname' => 'SUPERVISOR',
                ], 'itemname=:itemname', [':itemname' => 'KEPALA_TOKO']
        );
    }

    public function safeDown()
    {
        echo "m191204_090314_ubah_role_kepala_toko_to_supervisor does not support migration down.\n";
        return false;
    }

}
