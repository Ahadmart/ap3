<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>
<div class="medium-2 columns sidebar kiri">
   <!--<div id="logo">-->
      <!--<img src="<?php // echo Yii::app()->theme->baseUrl.'/img/'   ?>ahadmart-logo.png" />-->
   <!--</div>-->
   <?php if (!is_null($this->namaProfil)) {
      ?>
      <span class="secondary label">Customer</span><span class="label"><?php echo $this->namaProfil; ?></span>
      <?php
   }
   ?>
   <ul class="stack radius button-group">
      <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny button" accesskey="n"><span class="ak">N</span>ew</a></li>
      <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="success expand bigfont tiny button" accesskey="s"><span class="ak">S</span>uspended</a></li>
   </ul>
</div>
<?php echo $content; ?>
<?php
$this->endContent();
