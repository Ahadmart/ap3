<?php

class m190423_032430_set_default_supplier_barang extends CDbMigration
{

    public function safeUp()
    {
        $sql = "
            UPDATE supplier_barang sb
                    JOIN
                (SELECT 
                    MAX(id) id, barang_id
                FROM
                    supplier_barang
                GROUP BY barang_id
                HAVING MAX(`default`) = 0) AS t1 ON t1.id = sb.id 
            SET 
                `default` = :nilaiDefault
            ";

        $this->execute($sql, [':nilaiDefault' => 1]); //SupplierBarang::SUPPLIER_DEFAULT
    }

    public function safeDown()
    {
        echo "m190423_032430_set_default_supplier_barang does not support migration down.\n";
        return false;
    }

}
