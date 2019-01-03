<?php

class m190103_092027_create_auth_item_tools_cetaklabelrak extends CDbMigration
{

    public function safeUp()
    {
        $sql    = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";
        $params = [
            [':nama' => 'tools/cetaklabelrak.index', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cetaklabelrak.pilihprofil', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cetaklabelrak.pilihrak', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cetaklabelrak.tambahkanbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cetaklabelrak.hapus', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cetaklabelrak.hapussemua', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cekharga.caribarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'cetakLabelRak', ':tipe' => 1, ':deskripsi' => ''],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cetaklabelrak.index'],
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cetaklabelrak.pilihprofil'],
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cetaklabelrak.pilihrak'],
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cetaklabelrak.tambahkanbarang'],
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cetaklabelrak.hapus'],
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cetaklabelrak.hapussemua'],
            [':parent' => 'cetakLabelRak', ':child' => 'tools/cekharga.caribarang'],
            [':parent' => 'DATA_ENTRY', ':child' => 'cetakLabelRak'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m190103_092027_create_auth_item_tools_cetaklabelrak does not support migration down.\n";
        return false;
    }

}
