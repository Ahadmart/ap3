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
                    <?php echo CHtml::submitButton('cari', ['class' => 'button postfix', 'value' => 'Cari']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo CHtml::endForm();
    ?>
</div>
<script>
    $(document).ready(function() {
        $("#search-member-form").submit(function(event) {
            $(".alert-box").slideUp();
            $("#list-profil").addClass("grid-loading");
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
                    $("#list-profil").removeClass("grid-loading")
                })

            });

            event.preventDefault();
        });
    });
</script>