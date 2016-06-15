<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
   <div class="row small-collapse large-uncollapse">
      <div class="small-12 columns">
         <div class="block">
            <div class="top-bar block-header">
               <ul class="title-area">
                  <li class="name show-for-small-only"><h1><?php echo $this->boxHeader['small']; ?></h1></li>
                  <li class="name hide-for-small-only"><h1><?php echo $this->boxHeader['normal']; ?></h1></li>
               </ul>
               <section class="top-bar-section">
                  <?php
                  $this->widget('BTombolBox', array(
                      'htmlOptions' => array(
                          'class' => 'right'
                      ),
                      'encodeLabel' => false,
                      'id' => '',
                      'items' => $this->menu
                  ));
                  ?>
               </section>
            </div>
            <div class="block-content">
               <?php echo $content; ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
$this->endContent();
