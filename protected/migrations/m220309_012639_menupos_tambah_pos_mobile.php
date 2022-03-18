<?php

class m220309_012639_menupos_tambah_pos_mobile extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->update(
            'menu',
            [
                'link'   => null,
                'urutan' => 1,
            ],
            'id=:id',
            [':id' => 73]
        );
        $this->update(
            'menu',
            [
                'parent_id'  => 73,
                'root_id'    => 71,
                'nama'       => 'POS Desktop',
                'icon'       => '<i class="fa fa-desktop fa-fw"></i>',
                'link'       => '/pos/index',
                'keterangan' => 'POS untuk layar besar',
                'level'      => 2,
                'urutan'     => 1,
                'status'     => 1,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
            'id=:id',
            [':id' => 90]
        );
        $this->update(
            'menu',
            [
                'parent_id'  => 73,
                'root_id'    => 71,
                'nama'       => 'POS Mobile',
                'icon'       => '<i class="fa fa-mobile fa-fw"></i>',
                'link'       => '/posm/index',
                'keterangan' => 'POS untuk layar kecil',
                'level'      => 2,
                'urutan'     => 2,
                'status'     => 1,
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now,
            ],
            'id=:id',
            [':id' => 91]
        );
    }

    public function safeDown()
    {
        echo "m220309_012639_menupos_tambah_pos_mobile does not support migration down.\n";
        return false;
    }
}
