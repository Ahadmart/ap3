<div class="row">
    <div class="medium-6 large-4 columns">
        <div class="row collapse">
            <div class="small-6 columns">
                <span class="prefix">Est Sisa Stok <=</span>
            </div>
            <div class="small-2 columns">
                <input id="scan" type="text" style="text-align: right" value="7" />
            </div>
            <div class="small-4 columns">
                <span class="prefix">Hari</span>
            </div>
        </div>
    </div>
    <div class="medium-6 large-4 end columns">
        <?=
        CHtml::link('<i class="fa fa-calculator fa-fw"></i> <span class="ak">A</span>mbil Data PLS', '#', [
            'class'     => 'tiny bigfont button',
            'accesskey' => 'a',
            'id'        => 'tombol-ambil-pls'
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="error" style="display: none"></div>
    </div>
</div>

<script>
    var handler = function (e) {
        $("#tombol-ambil-pls").off();
        dataUrl = '<?php echo $this->createUrl('ambilpls', ['id' => $model->id]); ?>';
        dataKirim = {
            'ambil': true,
        };
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            beforeSend: function () {
                $("#tombol-ambil-pls").addClass("tombol-loading");
                $("#tombol-ambil-pls").html("<i class=\"fa fa-refresh fa-spin fa-fw\"></i> <span class=\"ak\">A</span>mbil Data PLS");
            },
            success: function (data) {
                if (data.sukses) {
                    $.fn.yiiGridView.update('pls-detail-grid')
                } else {
                    $(".error").html("Error " + data.error.code + ": " + data.error.msg).slideDown(500);
                }
            },
            complete: function () {
                $("#tombol-ambil-pls").removeClass("tombol-loading");
                $("#tombol-ambil-pls").html("<i class=\"fa fa-calculator fa-fw\"></i> <span class=\"ak\">A</span>mbil Data PLS");
                $("#tombol-ambil-pls").on("click", handler);
            }
        });
        return false;
    }

    $('#tombol-ambil-pls').on('click', handler);

    function ambilTotal() {
        $("#total-po").load("<?= $this->createAbsoluteUrl('po/ambiltotal', ['id'=>$model->id]) ?>");
        // console.log("<?= $this->createUrl('ambiltotal', ['id'=>$model->id]) ?>");
    }
</script>