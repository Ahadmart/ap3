<?php
/* @var $this CustomerdisplayController */

$this->breadcrumbs = array(
    'Customerdisplay',
);
?>

<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="logo" style="margin: 10px"/>
<div class="info" style="padding: 10px; margin-top: 20px;">
</div>

<script>
    function updateInfo(){
        $(".info").load('<?php echo $this->createUrl('getinfo'); ?>');
    };
    
    $(document).ready(function ()
    {
        setInterval('updateInfo()', 1500);
    });
</script>