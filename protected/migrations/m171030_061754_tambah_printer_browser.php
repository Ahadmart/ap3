<?php

class m171030_061754_tambah_printer_browser extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('device', [
            'tipe_id' => 5,
            'nama' => 'Printer Browser',
            'keterangan' => 'Print via Browser',
            'updated_by' => 1,
            'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m171030_061754_tambah_printer_browser does not support migration down.\n";
        return false;
    }

}
