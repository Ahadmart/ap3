<?php
/* @var $this MembershipController */
?>
<div class="row">
    <div class="small-12 column">
        <div data-alert class="alert-box radius" style="display:none">
            <span></span>
            <a href="#" class="close button">&times;</a>
        </div>
    </div>
</div>
<div class="form">
    <?php
    echo CHtml::beginForm($this->createUrl('search'), 'post', ['id' => 'search-member-form']);
    ?>
    <div class="row">
        <div class="small-12 columns">
            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix">Nomor/NoTelp/Nama</span>
                </div>
                <div class="small-6 columns">
                    <?php echo CHtml::textField('cari-input', '', ['autofocus' => 'autofocus']); ?>
                </div>
                <div class="small-3 columns">
                    <?php echo CHtml::submitButton('Cari', ['class' => 'button postfix', 'value' => 'cari']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo CHtml::endForm();
    ?>
</div>
<div class="row">
    <div class="small-12 columns">
        <table id="list-profil" style="width:100%">
            <thead>
                <tr>
                    <th onclick="sortTable(0)"><i class="fa fa-sort"></i> NOMOR</th>
                    <th onclick="sortTable(1)"><i class="fa fa-sort"></i> NO TELP</th>
                    <th onclick="sortTable(2)"><i class="fa fa-sort"></i> NAMA LENGKAP</th>
                    <th onclick="sortTable(3)"><i class="fa fa-sort"></i> TANGGAL LAHIR</th>
                    <th onclick="sortTable(3)"><i class="fa fa-sort"></i> PEKERJAAN</th>
                    <th onclick="sortTable(3)"><i class="fa fa-sort"></i> ALAMAT</th>
                    <th onclick="sortTable(3)"><i class="fa fa-sort"></i> KETERANGAN</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#search-member-form").submit(function(event) {
            $(".alert-box").slideUp();
            var formData = {
                data: $("#cari-input").val()
            };
            $.ajax({
                type: "POST",
                url: "<?= $this->createUrl('cari') ?>",
                data: formData,
            }).done(function(r) {
                // console.log(data);
                r = JSON.parse(r)
                $(".alert-box").slideUp(500, function() {
                    if (r.statusCode == 200) {
                        $(".alert-box").removeClass("alert");
                        $(".alert-box").addClass("warning");
                        $(".alert-box>span").html("Ditemukan " + r.data.count +
                            " profil")
                        isiTabel(r.data.profils)
                    } else {
                        $(".alert-box").removeClass("warning");
                        $(".alert-box").addClass("alert");
                        $(".alert-box>span").html(r.statusCode + ":" + r.error.type +
                            ". " + r.error.description)
                    }
                    $(".alert-box").slideDown(500)
                })

            });

            event.preventDefault();
        });
    });

    function isiTabel(data) {
        var tableBody = $("#list-profil>tbody");
        tableBody.html("");
        data.forEach(function(object) {
            var tr = document.createElement('tr');
            tr.innerHTML = '<td><a href="<?= $this->createUrl('/membership') ?>/' + object.nomor + '">' + object.nomor + '</a></td>' +
                '<td>' + object.nomor_telp + '</td>' +
                '<td>' + object.nama_lengkap + '</td>' +
                '<td>' + object.tanggal_lahir + '</td>' +
                '<td>' + object.pekerjaan + '</td>' +
                '<td>' + object.alamat + '</td>' +
                '<td>' + object.keterangan + '</td>';
            tableBody.append(tr);
        });
    }
</script>