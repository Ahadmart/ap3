<?php
$this->boxHeader['small'] = 'Import';
$this->boxHeader['normal'] = 'Import Pembelian';
?>

<div class="row">
    <div class="medium-6 columns">
        <div class="panel">
            <h4><small>dari</small> CSV</h4>
            <hr />
            <div class="row">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'upload-csv-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                ));
                ?>
                <div class="small-12 columns">
                    <?php echo $form->labelEx($modelCsvForm, 'profilId'); ?>
                    <?php
                    echo $form->dropDownList($modelCsvForm, 'profilId', CHtml::listData($supplierList, 'id', 'nama'), array(
                        'empty' => 'Pilih satu..',
                        'autofocus' => 'autofocus'
                    ));
                    ?>
                    <?php echo $form->error($modelCsvForm, 'profil_id', array('class' => 'error')); ?>
                </div>
                <div class="small-12 columns">
                    <input id="checkbox_profil" type="checkbox" name="semua_profil"><label for="checkbox_profil">Tampilkan semua profil</label>
                </div>
                <script>
                    $("#checkbox_profil").change(function () {
                        if (this.checked) {
                            console.log('semua');
                            $("#UploadCsvPembelianForm_profilId").load("<?php echo $this->createUrl('ambilprofil', array('tipe' => $this::PROFIL_ALL)); ?>");
                        } else {
                            console.log('supplier');
                            $("#UploadCsvPembelianForm_profilId").load("<?php echo $this->createUrl('ambilprofil', array('tipe' => $this::PROFIL_SUPPLIER)); ?>");
                        }
                    });
                </script>
                <div class="small-12 columns">
                    <?php //echo $form->labelEx($modelCsvForm, 'csv_file'); ?>
                    <?php echo $form->fileField($modelCsvForm, 'csvFile', array("class" => "tiny bigfont success button")); ?>
                    <?php echo $form->error($modelCsvForm, 'csvFile'); ?>
                </div>
                <div class="small-12 columns" id="info-nota-ada">

                </div>
                <div class="small-12 columns">
                    <?php
                    echo CHtml::submitButton('Upload CSV', array(
                        'name' => 'upload-csv',
                        "class" => "tiny bigfont expand button"));
                    ?>
                    <?php echo $form->errorSummary($modelCsvForm); ?>
                </div>
                <?php
                $this->endWidget();
                ?>
            </div>

        </div>
    </div>
    <?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.csv.js', CClientScript::POS_HEAD);
    ?>
    <script>
        $(document).ready(function () {
            if (isAPIAvailable()) {
                $('#UploadCsvPembelianForm_csvFile').bind('change', handleFileSelect);
            }
        });

        function isAPIAvailable() {
            // Check for the various File API support.
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                // Great success! All the File APIs are supported.
                return true;
            } else {
                // source: File API availability - http://caniuse.com/#feat=fileapi
                // source: <output> availability - http://html5doctor.com/the-output-element/
                document.writeln('The HTML5 APIs used in this form are only available in the following browsers:<br />');
                // 6.0 File API & 13.0 <output>
                document.writeln(' - Google Chrome: 13.0 or later<br />');
                // 3.6 File API & 6.0 <output>
                document.writeln(' - Mozilla Firefox: 6.0 or later<br />');
                // 10.0 File API & 10.0 <output>
                document.writeln(' - Internet Explorer: Not supported (partial support expected in 10.0)<br />');
                // ? File API & 5.1 <output>
                document.writeln(' - Safari: Not supported<br />');
                // ? File API & 9.2 <output>
                document.writeln(' - Opera: Not supported');
                return false;
            }
        }

        function handleFileSelect(evt) {
            var files = evt.target.files;
            var file = files[0];
            baca(file);
        }

        function baca(file) {
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload = function (event) {
                var jumlah = 0;
                csv = event.target.result;
                // console.log(csv);
                var data = $.csv.toArrays(csv);
                for (var row in data) {
                    if (row > 0) {
                        subTotal = data[row][3] * data[row][5];
                        jumlah += subTotal;
                        //console.log(subTotal);
                    }
                }
                var namaFile = escape(file.name);
                var nF = namaFile.split('-');
                var nomor = nF[0];
                var profilId = $("#UploadCsvPembelianForm_profilId").val();
                tampilkanInfo(profilId, nomor, jumlah);
            };
            reader.onerror = function () {
                alert('Unable to read ' + file.fileName);
            };
        }

        function tampilkanInfo(profilId, nomorRef, nominal) {
            //console.log(profilId + ' | ' + nomorRef + ' | ' + nominal);
            $("#info-nota-ada").html('');
            var dataKirim = {
                profilId: profilId,
                nomorRef: nomorRef,
                nominal: nominal
            };
            $.ajax({
                type: 'GET',
                url: '<?php echo $this->createUrl('caribyref'); ?>',
                data: dataKirim,
                success: function (data) {
                    if (data.ada) {
                        $("#info-nota-ada").html(data.pembelian);
                    }

                }
            });
        }
    </script>
    <div class="medium-6 columns">
        <div class="panel">
            <h4><small>dari</small> Database</h4>
            <hr />
            <form method="POST">
                <div class="row">
                    <div class="small-12 columns">
                        <div class="row collapse postfix-round">
                            <div class="small-9 columns">
                                <input type="text" name="nomor" placeholder="Masukkan Nomor Pembelian AhadPOS2" autofocus="autofocus">
                            </div>
                            <div class="small-3 columns">
                                <input class="button postfix" type="submit" value="Go" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <label for="db_source">Database ahadPOS 2</label>
                        <input id="db_source" type="text" name="database" value="gudang" placeholder="Database ahadpos2 (sumber)" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
