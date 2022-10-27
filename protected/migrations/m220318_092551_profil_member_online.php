<?php

class m220318_092551_profil_member_online extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert(
            'profil_tipe',
            [
                'nama'       => 'Member Online',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ]
        );

        $now = date('Y-m-d H:i:s');
        $this->insert(
            'profil',
            [
                'id'         => 3,
                'tipe_id'    => 4,
                'nama'       => 'Member Online',
                'keterangan' => 'Profil offline untuk member online',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ]
        );
    }

    public function safeDown()
    {
        echo "m220318_092551_profil_member_online does not support migration down.\n";
        return false;
    }
}
