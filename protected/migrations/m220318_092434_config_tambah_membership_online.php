<?php

class m220318_092434_config_tambah_membership_online extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert(
            'config',
            [
                'nama'       => 'pos.showmember',
                'nilai'      => '1',
                'deskripsi'  => '0: sembunyikan; 1: tampilkan fitur membership mandiri (offline) ',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ]
        );
        $this->insert(
            'config',
            [
                'nama'       => 'pos.showmembership',
                'nilai'      => '1',
                'deskripsi'  => '0: sembunyikan; 1: tampilkan fitur ahadMembership (online) ',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ]
        );
    }

    public function safeDown()
    {
        echo "m220318_092434_config_tambah_membership_online does not support migration down.\n";
        return false;
    }
}
