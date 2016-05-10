<?php
/* @var $this CetakformsoController */

$this->breadcrumbs = array(
    'Cetakformso',
);
$this->boxHeader['small'] = 'Form SO';
$this->boxHeader['normal'] = 'Cetak Form SO';
?>

<?php $this->renderPartial('_form_input', ['model' => $modelForm]); ?>
<div class="row">
    <div class="small-12 column">
        <div id="tabel-rak" style="display: none">
            <?php $this->renderPartial('_rak', array('rak' => $rak)); ?>
        </div> 
    </div>
</div>
<script>
    $("#tombol-browse-rak").click(function () {
        $("#tabel-rak").slideToggle(500);
        $("input[name='RakBarang[nama]']").focus();
    });

    $("body").on("click", "a.pilih.rak", function () {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: function(data){
                isiRak(data);
                isiKategori(data);
            }
        });
        return false;
    });

    function isiRak(data) {
        console.log(data);
        $("#rak").val(data.nama);
        $("#tabel-rak").slideUp(500);
        $("#CetakStockOpnameForm_rakId").val(data.id);
    }

    function isiKategori(data) {
        $("#CetakStockOpnameForm_kategoriId").load("<?php echo $this->createUrl("getkategoriopt"); ?>",{'rak-id':data.id});
    }
</script>