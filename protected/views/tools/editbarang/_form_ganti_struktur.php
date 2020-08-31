<div class="row medium-collapse">
    <div class="small-12 columns">
        <h6>Ganti Struktur</h6>
    </div>
    <div class="medium-4 columns" id="grid1-container">
        <?php
        $this->renderPartial('_grid1', [
            'lv1' => $lv1
        ]);
        ?>  
    </div>
    <div class="medium-4 columns" id="grid2-container">
        <?php
        $this->renderPartial('_grid2', [
            'lv2' => $strukturDummy
        ]);
        ?>  
    </div>
    <div class="medium-4 columns" id="grid3-container"">
        <?php
        $this->renderPartial('_grid3', [
            'lv3' => $strukturDummy
        ]);
        ?>  
    </div>
    <input type="hidden" id="input-struktur">
    <div class="small-12 columns">
        <?php
        echo CHtml::ajaxLink('Ganti', $this->createUrl('gantistruktur'), [
            'data'    => 'js:{\'struktur-id\' : $("#input-struktur").val(), \'items\': $(\'#barang-grid\').yiiGridView(\'getChecked\', \'kolomcek\')}',
            'method'  => 'POST',
            'success' => "function (r) {
                            if (r.sukses) {
                                $.gritter.add({
                                    title: 'Sukses',
                                    text: r.rowAffected + ' item berganti kategori menjadi: ' + r.namaStruktur,
                                    time: 3000
                                });
                                $('#barang-grid').yiiGridView('update');
                            } else {
                                $.gritter.add({
                                    title: 'Error ' + r.error.code,
                                    text: r.error.msg,
                                    time: 5000
                                });
                            }
                            $('#ganti-struktur').toggle(500);
                            }"
                ], [
            'class' => 'tiny bigfont success right button',
            'id'    => 'tombol-simpan-struktur']);
        ?>
    </div>
</div>
<script>
    function lv1Dipilih(id) {
        var lv1Id = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(lv1Id) || !lv1Id.length) {
            console.log("1 tidak dipilih");
<?php /* render nothing */ ?>
            $("#grid2-container").load("<?= $this->createUrl("barang/renderstrukturgrid") ?>", {level: 2, parent: 0});
            $('#input-struktur').val("");
        } else {
            console.log(lv1Id[0] + ":1 dipilih");
            $("#grid2-container").load("<?= $this->createUrl("barang/renderstrukturgrid") ?>", {level: 2, parent: lv1Id[0]});
        }
        $("#grid3-container").load("<?= $this->createUrl("barang/renderstrukturgrid") ?>", {level: 3, parent: 0});
    }
    function lv2Dipilih(id) {
        var lv2Id = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(lv2Id) || !lv2Id.length) {
            console.log("2 tidak dipilih");
<?php /* render nothing */ ?>
            $("#grid3-container").load("<?= $this->createUrl("barang/renderstrukturgrid") ?>", {level: 3, parent: 0});
            $('#input-struktur').val("");
        } else {
            console.log(lv2Id[0] + ":2 dipilih");
            $("#grid3-container").load("<?= $this->createUrl("barang/renderstrukturgrid") ?>", {level: 3, parent: lv2Id[0]});
        }
    }
    function lv3Dipilih(id) {
        var lv3Id = $('#' + id).yiiGridView('getSelection');
        if (!Array.isArray(lv3Id) || !lv3Id.length) {
            console.log("3 tidak dipilih");
            $('#input-struktur').val("");
        } else {
            console.log(lv3Id[0] + ":3 dipilih");
            $('#input-struktur').val(lv3Id[0]);
        }
    }
</script>