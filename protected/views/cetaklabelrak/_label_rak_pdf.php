<html>
    <head>
        <title>Label Rak</title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-laporan.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
    </head>
    <body>
       <?php 
       foreach ($barang as $labelBarang){
           ?>
        <div style="border: 1px solid black">
            <h6><?php echo $labelBarang->barang->nama; ?></h6>
        </div>
        <?php
       }
       ?>

    </body>
</html>