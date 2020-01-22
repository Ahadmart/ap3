<?php

class m200122_013022_authitem_ubahreport_toprank extends CDbMigration
{

    public function safeUp()
    {
        $this->insert('AuthItem', [
            'name' => 'report.printtoprank',
            'type' => 0,
        ]);
        $this->insert('AuthItemChild', [
            'parent' => 'laporanSemua',
            'child'  => 'report.printtoprank',
        ]);
        $this->delete('AuthItemChild', "child = 'report.toprankpdf'");
        $this->delete('AuthItem', "name = 'report.toprankpdf'");
    }

    public function safeDown()
    {
        echo "m200122_013022_authitem_ubahreport_toprank does not support migration down.\n";
        return false;
    }

}
