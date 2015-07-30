<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<div class="medium-4 large-3 columns">
   <form>
      <div class="row collapse">
         <div class="small-3 large-2 columns">
            <span class="prefix"><i class="fa fa-barcode fa-2x"></i></span>
         </div>
         <div class="small-9 large-10 columns">
            <input id="scan" type="text"  placeholder="Scan [B]arcode" accesskey="b" autofocus="autofocus" autocomplete="off"/>
         </div>
      </div>
</div>
<div class="medium-4 large-2 columns">
   <div class="row collapse">
      <div class="small-3 large-3 columns">
         <span class="prefix huruf"><b>Q</b>ty</span>
      </div>
      <div class="small-6 large-4 columns">
         <input id="qty" type="text"  value="1" placeholder="[Q]ty" accesskey="q"/>
      </div>
      <div class="small-3 large-5 columns">
         <a id="tombol-pilih" href="#" class="button postfix">Pilih</a>
         <?php
         /*
           echo CHtml::ajaxLink('T<span class="ak">a</span>mbah', $this->createUrl('tambahdetail', array('id' => $model->id)), array(
           'data' => "simpan=true",
           'type' => 'POST',
           'success' => 'function(data) {
           if (data.sukses) {
           location.reload(true);
           }
           }'
           ), array(
           'class' => 'button postfix',
           'accesskey' => 'a'
           )
           );
          * 
          */
         ?>
      </div>
   </div>
</form>
</div>
<div class="medium-4 column">
   <div class="row collapse">
      <div class="small-3 large-2 columns">
         <span class="prefix"><i class="fa fa-search fa-2x"></i></span>
      </div>
      <div class="small-6 large-6 columns">
         <input id="namabarang" type="text"  placeholder="[C]ari Barang" accesskey="c"/>
      </div>
      <div class="small-3 large-4 columns">
         <a href="#" id="cari" class="button postfix">Cari</a>
      </div>
   </div>
</div>
<script>
   $("#tombol-pilih").click(function () {
      showStruk();
      return false;
   });

   function showStruk() {
      var datakirim = {
         'pilih': true,
         'barcode': $("#scan").val(),
         'qty': $("#qty").val(),
         "PenjualanDetail[nomorPenjualan]": ''
      };
<?php /* Update grid struk dengan data penjualan untuk barcode terpilih */ ?>
      $('#penjualan-detail-grid').yiiGridView('update', {
         data: datakirim
      });
      $("#retur-penjualan-detail").hide(100, function () {
         $("#struk-list").show(100, function () {
//            $("input[name='PenjualanDetail[nomorPenjualan]']").val("");
//            $("input[name='PenjualanDetail[nomorPenjualan]']").focus();
            $("a.pilih.struk:first").focus();
         });
      });
   }

   $("#scan").keyup(function (e) {
      if (e.keyCode === 13) {
         $("#qty").focus();
         $("#qty").select();
      }
   });

   $("#qty").keyup(function (e) {
      if (e.keyCode === 13) {
         $("#tombol-pilih").click();
      }
   });

   $(document).ready(function () {
      $("#scan").focus();
   });

   function updateTotal() {
      var dataurl = "<?php echo $this->createUrl('total', array('id' => $model->id)); ?>";
      $.ajax({
         url: dataurl,
         type: "GET",
         dataType: "json",
         success: function (data) {
            if (data.sukses) {
               $("#total-penjualan").text(data.totalF);
               console.log(data.totalF);
            }
         }
      });
      $("#scan").val("");
      $("#scan").focus();
      $("#qty").val("1");
   }
<?php
/*
 * Tombol cari diclik
 * 1. retur penjualan hide
 * 2. barang-list show
 * 3. struk-list hide
 */
?>
   $("#cari").click(function () {
      var datakirim = {
         'cariBarang': true,
         'namaBarang': $("#namabarang").val()
      };
      $('#barang-grid').yiiGridView('update', {
         data: datakirim
      });
      $("#retur-penjualan-detail").hide(100, function () {
         $("#barang-list").show(100, function () {
            $("#namabarang").val("");
            $("#cari").focus();
         });
         $("#struk-list").hide(100);
      });
      return false;
   });

   $("#namabarang").keyup(function (e) {
      if (e.keyCode === 13) {
         $("#cari").click();
      }
      return false;
   });
</script>

