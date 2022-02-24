<?php
/* @var $this MembershipController */

$this->breadcrumbs = [
    'Membership' => ['index'],
    $data->nomor,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Member: ' . $data->nomor;
?>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_view_member', [
                'data' => $data
            ]);
            // print_r($data);
            ?>
        </div>
    </div>
</div>