<?php
$this->boxHeader['small'] = 'Tambah';
$this->boxHeader['normal'] = 'Tambah Item Otorisasi';
?>

<div class="row">
    <div class="medium-6 columns">
        <div class="panel">
            <h4><small>Item</small> Input</h4>
            <hr />
            <?php
            $this->renderPartial('_form', ['model' => $model]);
            ?>
        </div>
    </div>
    <div class="medium-6 columns">
        <div class="panel">
            <div class="row collapse">
                <div class="small-8 columns">
                    <h4><small>Operations</small> Auto Generate</h4>        
                </div>
                <div class="small-4 columns">
                    <a class="right tiny bigfont button tombol-gensim">Go</a>
                </div>
                <hr />
            </div>

            <?php
            $this->renderPartial('_autogen', ['model' => $model]);
            ?>
        </div>
    </div>
</div>
<script>
    $(".tombol-gensim").click(function () {
        $.ajax({
            url: '<?= $this->createUrl('gensim'); ?>',
            success: function (data) {
                if (data.sukses) {
                    console.log("Ool iz wel")
                    $("#gensim-container").html(data.message);
                    $(".tombol-exec").show(500);
                } else {
                    $("#gensim-container").html("<h1>Sam Ting Wong !</h1>");
                }
            }
        });
        return false;
    });
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
