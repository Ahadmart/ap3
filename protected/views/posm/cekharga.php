<div class="row">
    <div class="small-12 columns">
        <?php
        $this->renderPartial('//tools/cekharga/_cekharga', [
            'urlCallback' => $urlCallback,
            'scanBarcode' => $scanBarcode,
        ]);
        ?>
    </div>
</div>