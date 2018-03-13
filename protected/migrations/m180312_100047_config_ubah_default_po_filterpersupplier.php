<?php

class m180312_100047_config_ubah_default_po_filterpersupplier extends CDbMigration
{
    public function safeUp()
    {
        $this->update('config', ['nilai'=>1], "nama='po.filterpersupplier'");
    }

    public function safeDown()
    {
        echo "m180312_100047_config_ubah_default_po_filterpersupplier does not support migration down.\n";
        return false;
    }
}
