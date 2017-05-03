<h4><small>Daftar</small> Tags</h4>
<hr />
<div class="row collapse" style="margin-bottom:20px">
    <div class="small-12 columns">
        <?php
        $fst = true;
        foreach ($curTags as $tag) {
            //echo!$fst ? ' ' : '';
            ?>
            <span class="secondary label"><?= $tag ?></span>
            <?php
            $fst = false;
        }
        ?>
    </div>
</div>
