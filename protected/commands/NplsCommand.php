<?php

class NplsCommand extends CConsoleCommand
{

    public function actionIndex()
    {
        echo "DROP TABLE IF EXISTS rekap_ads \n";
        //Yii::app()->db->createCommand()->dropTable('rekap_ads');
        Yii::app()->db->createCommand('DROP TABLE IF EXISTS `rekap_ads`')->execute();

        $dbEngine = 'InnoDB';

        echo "CREATE TABLE rekap_ads \n";
        Yii::app()->db->createCommand()->createTable('rekap_ads', [
            'barang_id'  => 'INT(10) UNSIGNED NOT NULL',
            'qty'        => "INT(11) NOT NULL DEFAULT '0'",
            'ads'        => "FLOAT DEFAULT '0'",
            'stok'       => "INT(11) NOT NULL DEFAULT '0'",
            'sisa_hari'  => "FLOAT DEFAULT '0'",
            'updated_at' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'PRIMARY KEY (`barang_id`)',
            'KEY `sisa_hari_idx` (`sisa_hari`)',
        ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $intervalHari = 40;
        $sqlAds       = "
            INSERT INTO rekap_ads
            SELECT
                t_jualan.*,
                t_jualan.qty / :interval ads,
                t_stok.qty stok,
                t_stok.qty / (t_jualan.qty / :interval) sisa_hari,
                now()
            FROM
                (SELECT
                    barang_id, SUM(qty) qty
                FROM
                    penjualan_detail
                WHERE
                    created_at BETWEEN DATE_SUB(NOW(), INTERVAL :interval DAY) AND NOW()
                GROUP BY barang_id) AS t_jualan
                    JOIN
                (SELECT
                    barang_id, SUM(qty) qty
                FROM
                    inventory_balance
                GROUP BY barang_id
                HAVING SUM(qty) >= 0) AS t_stok ON t_stok.barang_id = t_jualan.barang_id
                ";
        echo "INSERT INTO rekap_ads \n";
        echo Yii::app()->db->createCommand($sqlAds)->bindValue(':interval', $intervalHari)->execute() . ' row(s) affected' . PHP_EOL;
        return 1;
    }
}
