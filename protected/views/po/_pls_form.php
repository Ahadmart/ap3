<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo CHtml::activeLabelEx($modelReportPls, 'jumlahHari', ['data-tooltip', 'title' => "Lakukan Analisa penjualan barang selama beberapa hari yang lalu"]);
        echo CHtml::activeTextField($modelReportPls, 'jumlahHari', ['value' => empty($modelReportPls->jumlahHari) ? '40' : $modelReportPls->jumlahHari, 'data-tooltip', 'class' => 'has-tip', 'title' => "Lakukan Analisa penjualan barang selama beberapa hari yang lalu"]);
        // echo $form->error($model, 'jumlahHari', ['class' => 'error']);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo CHtml::activeLabelEx($modelReportPls, 'orderPeriod', ['data-tooltip', 'title' => "Hitung jumlah stok yang musti dipesan untuk ketersediaan jangka waktu ini"]);
        echo CHtml::activeTextField($modelReportPls, 'orderPeriod', ['value' => empty($modelReportPls->orderPeriod) ? '7' : $modelReportPls->orderPeriod, 'data-tooltip', 'class' => 'has-tip', 'title' => "Hitung jumlah stok yang musti dipesan untuk ketersediaan jangka waktu ini"]);
        // echo $form->error($model, 'sisaHariMax', ['class' => 'error']);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo CHtml::activeLabelEx($modelReportPls, 'leadTime', ['data-tooltip', 'title' => "Jarak antara order, sampai ordernya sampai"]);
        echo CHtml::activeTextField($modelReportPls, 'leadTime', ['value' => empty($modelReportPls->leadTime) ? '0' : $modelReportPls->leadTime, 'data-tooltip', 'class' => 'has-tip', 'title' => "Jarak antara order, sampai ordernya sampai"]);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo CHtml::activeLabelEx($modelReportPls, 'ssd', ['data-tooltip', 'title' => "Stok jaga-jaga"]);
        echo CHtml::activeTextField($modelReportPls, 'ssd', ['value' => empty($modelReportPls->ssd) ? '0' : $modelReportPls->ssd, 'data-tooltip', 'class' => 'has-tip', 'title' => "Stok jaga-jaga"]);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns end">
        <?php
        echo CHtml::activeLabelEx($modelReportPls, 'rakId', ['data-tooltip', 'title' => "Pilih Rak Barang (Opsional)"]);
        echo CHtml::activeDropDownList($modelReportPls, 'rakId', RakBarang::listPerSupplier($model->profil_id), ['data-tooltip', 'class' => 'has-tip', 'title' => "Pilih Rak Barang (Opsional)", 'prompt' => '[SEMUA]']);
        // echo $form->error($model, 'rakId', ['class' => 'error']);
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo CHtml::activeLabelEx($modelReportPls, 'strukLv1', ['data-tooltip', 'title' => "Pilih Struktur Lv 1"]);
        echo CHtml::activeDropDownList($modelReportPls, 'strukLv1', StrukturBarang::listStrukLv1(), ['data-tooltip', 'class' => 'has-tip', 'title' => "Pilih Struktur Lv 1 (Opsional)"]);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php
        $listLv2 = [];
        if (!empty($modelReportPls->strukLv2)){
            $listLv2 = StrukturBarang::listStrukLv2($modelReportPls->strukLv1);
        }
        echo CHtml::activeLabelEx($modelReportPls, 'strukLv2', ['data-tooltip', 'title' => "Pilih Struktur Lv 1"]);
        echo CHtml::activeDropDownList($modelReportPls, 'strukLv2', $listLv2, ['data-tooltip', 'class' => 'has-tip', 'title' => "Pilih Struktur Lv 2 (Opsional)", 'prompt' => '[SEMUA]']);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns end">
        <?php
        $listLv3 = [];
        if (!empty($modelReportPls->strukLv3)){
            $listLv3 = StrukturBarang::listStrukLv3($modelReportPls->strukLv2);
        }
        echo CHtml::activeLabelEx($modelReportPls, 'strukLv3', ['data-tooltip', 'title' => "Pilih Struktur Lv 3"]);
        echo CHtml::activeDropDownList($modelReportPls, 'strukLv3', $listLv3, ['data-tooltip', 'class' => 'has-tip', 'title' => "Pilih Struktur Lv 3 (Opsional)", 'prompt' => '[SEMUA]']);
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::activeCheckBox($modelReportPls, 'semuaBarang', ['data-tooltip', 'title' => "Masukkan juga barang tanpa penjualan"]); ?>
        <?php echo CHtml::activeLabelEx($modelReportPls, 'semuaBarang', ['data-tooltip', 'title' => "Masukkan juga barang tanpa penjualan"]); ?>
        <?=
        CHtml::link('<i class="fa fa-calculator fa-fw"></i> <span class="ak">A</span>nalisa Data PLS', '#', [
            'class'     => 'tiny bigfont button',
            'accesskey' => 'a',
            'id'        => 'tombol-analisa-pls',
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
    var handler = function(e) {
        $("#tombol-analisa-pls").off();
        dataUrl = "<?php echo $this->createUrl('ambilpls', ['id' => $model->id]); ?>";
        dataKirim = {
            'ambil': true,
            'hariPenjualan': $("#ReportPlsForm_jumlahHari").val(),
            'orderPeriod': $("#ReportPlsForm_orderPeriod").val(),
            'leadTime': $("#ReportPlsForm_leadTime").val(),
            'ssd': $("#ReportPlsForm_ssd").val(),
            'rakId': $("#ReportPlsForm_rakId").val(),
            'strukLv1': $("#ReportPlsForm_strukLv1").val(),
            'strukLv2': $("#ReportPlsForm_strukLv2").val(),
            'strukLv3': $("#ReportPlsForm_strukLv3").val(),
            'semuaBarang': $("#ReportPlsForm_semuaBarang").is(':checked'),
        };
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            beforeSend: function() {
                $("#tombol-analisa-pls").addClass("tombol-loading");
                $("#tombol-analisa-pls").html("<i class=\"fa fa-refresh fa-spin fa-fw\"></i> <span class=\"ak\">A</span>mbil Data PLS");
                $(".error").html("").slideUp(500);
            },
            success: function(data) {
                if (data.sukses) {
                    $.fn.yiiGridView.update('pls-detail-grid');
                } else {
                    $(".error").html("Error " + data.error.code + ": " + data.error.msg).slideDown(500);
                }
            },
            complete: function() {
                $("#tombol-analisa-pls").removeClass("tombol-loading");
                $("#tombol-analisa-pls").html("<i class=\"fa fa-calculator fa-fw\"></i> <span class=\"ak\">A</span>nalisa Data PLS");
                $("#tombol-analisa-pls").on("click", handler);
            }
        });
        return false;
    }

    $('#tombol-analisa-pls').on('click', handler);

    function ambilTotal() {
        $("#total-po").load("<?= $this->createAbsoluteUrl('po/ambiltotal', ['id' => $model->id]) ?>");
        // console.log("<?= $this->createUrl('ambiltotal', ['id' => $model->id]) ?>");
    }

    $("#ReportPlsForm_strukLv1").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportPlsForm_strukLv2']").text('Loading..');
        $("#ReportPlsForm_strukLv2").load("<?= $this->createUrl('ambilstrukturlv2', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportPlsForm_strukLv2']").text('Struktur Level 2');
        })
        $("#ReportPlsForm_strukLv3").html("<option value=''>[SEMUA]</option>")
    })

    $("#ReportPlsForm_strukLv2").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportPlsForm_strukLv3']").text('Loading..');
        $("#ReportPlsForm_strukLv3").load("<?= $this->createUrl('ambilstrukturlv3', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportPlsForm_strukLv3']").text('Struktur Level 3');
        })
    })
</script>