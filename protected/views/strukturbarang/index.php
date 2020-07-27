<?php
/* @var $this StrukturbarangController */
/* @var $lv1 StrukturBarang */

$this->breadcrumbs = [
    'Struktur Barang' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Struktur Barang';
$this->boxHeader['normal'] = 'Struktur Barang';

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
?>
<div class="row">
    <div class="medium-4 columns">
        <div class="row collapse">
            <div class="small-8 columns">
                <input type="text" id="input-tambah-lv1" placeholder="Struktur Level 1">
            </div>
            <div class="small-4 columns">
                <?php
                echo CHtml::ajaxLink('Tambah', $this->createUrl('tambahlv1'), [
                    'data'    => 'js:{\'nama\' : $("#input-tambah-lv1").val()}',
                    'method'  => 'POST',
                    'success' => "function () {
                                $('#input-tambah-lv1').val('');
                                $.fn.yiiGridView.update('lv1-grid');
                                $('#input-tambah-lv2').prop('disabled', true);
                                $('#input-tambah-lv3').prop('disabled', true);
                            }"
                        ], [
                    'class' => 'button postfix',
                    'id'    => 'tombol-tambah-lv1']);
                ?>
            </div>
        </div>
        <?php
        $this->renderPartial('_grid1', [
            'lv1' => $lv1
        ]);
        ?>        
    </div>
    <div class="medium-4 columns">
        <div class="row collapse">
            <div class="small-8 columns">
                <input type="text" id="input-tambah-lv2" placeholder="Struktur Level 2" disabled>
            </div>
            <div class="small-4 columns">
                <?php
                echo CHtml::ajaxLink('Tambah', $this->createUrl('tambahlv2'), [
                    'data'    => 'js:{nama : $("#input-tambah-lv2").val(), parent: $("#input-tambah-lv2").attr("data-parent")}',
                    'method'  => 'POST',
                    'success' => "function (r) {
                                $('#input-tambah-lv2').val('');
                                $('#grid2-container').html(r);
                                $('#input-tambah-lv3').prop('disabled', true);
                            }"
                        ], [
                    'class' => 'button postfix',
                    'id'    => 'tombol-tambah-lv2']);
                ?>
            </div>
        </div>
        <div id="grid2-container">
            <?php
            $this->renderPartial('_grid2', [
                'lv2' => $lv2
            ]);
            ?>        
        </div>
    </div>
    <div class="medium-4 columns">
        <div class="row collapse">
            <div class="small-8 columns">
                <input type="text" id="input-tambah-lv3" placeholder="Struktur Level 3" disabled>
            </div>
            <div class="small-4 columns">
                <?php
                echo CHtml::ajaxLink('Tambah', $this->createUrl('tambahlv3'), [
                    'data'    => 'js:{nama : $("#input-tambah-lv3").val(), parent: $("#input-tambah-lv3").attr("data-parent")}',
                    'method'  => 'POST',
                    'success' => "function (r) {
                                $('#input-tambah-lv3').val('');
                                $('#grid3-container').html(r);
                            }"
                        ], [
                    'class' => 'button postfix',
                    'id'    => 'tombol-tambah-lv3']);
                ?>
            </div>
        </div>
        <div id="grid3-container">
            <?php
            $this->renderPartial('_grid3', [
                'lv3' => $lv3
            ]);
            ?>  
        </div>
    </div>
</div>
<script>
    $(document).on('ready ajaxComplete', function () {
        $("#lv1-grid table tbody").sortable({
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    if ($(this).attr('data-lv1-urutan') != (index + 1)) {
                        $(this).attr('data-lv1-urutan', (index + 1)).addClass('changed');
                    }
                });
                simpanUrutanLv1();
            }
        });
        $("#lv2-grid table tbody").sortable({
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    if ($(this).attr('data-lv2-urutan') != (index + 1)) {
                        $(this).attr('data-lv2-urutan', (index + 1)).addClass('changed');
                    }
                });
                simpanUrutanLv2();
            }
        });
        $("#lv3-grid table tbody").sortable({
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    if ($(this).attr('data-lv3-urutan') != (index + 1)) {
                        $(this).attr('data-lv3-urutan', (index + 1)).addClass('changed');
                    }
                });
                simpanUrutanLv3();
            }
        });
        $(".editable-nama").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function (response, newValue) {
                if (response.sukses) {
                    //$.fn.yiiGridView.update("pembelian-detail-grid");
                    //updateTotal();
                }
            }
        });
    });

    function simpanUrutanLv1() {
        var items = [];
        $('.changed').each(function () {
            items.push([$(this).attr('data-lv1-index'), $(this).attr('data-lv1-urutan')]);
            $(this).removeClass('changed');
        });

        $.ajax({
            url: '<?= $this->createUrl('updateurutan') ?>',
            method: 'POST',
            dataType: 'text',
            data: {
                ganti: 1,
                items: items
            }, success: function (r) {
                console.log(r);
                $.fn.yiiGridView.update("lv1-grid");
            }
        })
    }

    function simpanUrutanLv2() {
        var items = [];
        $('.changed').each(function () {
            items.push([$(this).attr('data-lv2-index'), $(this).attr('data-lv2-urutan')]);
            $(this).removeClass('changed');
        });

        $.ajax({
            url: '<?= $this->createUrl('updateurutan') ?>',
            method: 'POST',
            dataType: 'text',
            data: {
                ganti: 1,
                items: items
            }, success: function (r) {
                console.log(r);
                $("#grid1-container").load("<?= $this->createUrl("rendergrid") ?>", {level: 2, parent: $("#input-tambah-lv2").attr("data-parent")});
            }
        })
    }

    function simpanUrutanLv3() {
        var items = [];
        $('.changed').each(function () {
            items.push([$(this).attr('data-lv3-index'), $(this).attr('data-lv3-urutan')]);
            $(this).removeClass('changed');
        });

        $.ajax({
            url: '<?= $this->createUrl('updateurutan') ?>',
            method: 'POST',
            dataType: 'text',
            data: {
                ganti: 1,
                items: items
            }, success: function (r) {
                console.log(r);
            }
        })
    }

    function lv1Dipilih(id) {
        var lv1Id = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(lv1Id) || !lv1Id.length) {
            $("#input-tambah-lv2").removeAttr("data-parent", );
            $("#input-tambah-lv2").prop("disabled", true);
            $("#input-tambah-lv3").removeAttr("data-parent", );
            $("#input-tambah-lv3").prop("disabled", true);
            console.log("Lv1 tidak dipilih");
            //console.log(lv1Id[0]);
            $("#grid2-container").load("<?= $this->createUrl("rendergrid") ?>", {level: 2, parent: 0});
            $("#grid3-container").load("<?= $this->createUrl("rendergrid") ?>", {level: 3, parent: 0});
        } else {
            $("#input-tambah-lv2").attr("data-parent", lv1Id[0]);
            $("#input-tambah-lv2").prop("disabled", false);
            $("#input-tambah-lv3").prop("disabled", true);
            console.log("Lv1 dipilih");

            $.ajax({
                url: '<?= $this->createUrl('telahdipilihlv1') ?>',
                method: 'POST',
                dataType: 'text',
                data: {
                    dipilih: 1,
                    id: lv1Id,
                }, success: function (r) {
                    $("#grid2-container").html(r);
                    $("#grid3-container").load("<?= $this->createUrl("rendergrid") ?>", {level: 3, parent: 0});
                }
            })
        }
    }

    function lv2Dipilih(id) {
        var lv2Id = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(lv2Id) || !lv2Id.length) {
            $("#input-tambah-lv3").removeAttr("data-parent", );
            $("#input-tambah-lv3").prop("disabled", true);
        } else {
            $("#input-tambah-lv3").attr("data-parent", lv2Id[0]);
            $("#input-tambah-lv3").prop("disabled", false);
            //console.log("0 dipilih");
        }

        $.ajax({
            url: '<?= $this->createUrl('telahdipilihlv2') ?>',
            method: 'POST',
            dataType: 'text',
            data: {
                dipilih: 1,
                id: lv2Id,
            }, success: function (r) {
                $("#grid3-container").html(r);
            }
        })
    }

    function lv3Dipilih(id) {
        var lv3Id = $('#' + id).yiiGridView('getSelection');
        console.log(lv3Id[0]);
    }

    $("#input-tambah-lv1").keydown(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah-lv1").click();
        }
    });

    $("#input-tambah-lv2").keydown(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah-lv2").click();
        }
    });

    $("#input-tambah-lv3").keydown(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-tambah-lv3").click();
        }
    });

</script>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-ui-sortable.min.js', CClientScript::POS_HEAD);
