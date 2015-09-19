<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/pos'); ?>

<div class="medium-2 columns sidebar kiri">
   <div id="logo">
      <img src="<?php echo Yii::app()->theme->baseUrl.'/img/' ?>ahadmart-logo.png" />
   </div>
   <!--   <div  id="info">
         <table id="info-tabel">
            <tbody>
               <tr>
                  <td class="key">Struk# :</td>
                  <td>1234567</td>
               </tr>
               <tr>
                  <td class="key">Cust :</td>
                  <td>PD. KACANG</td>
               </tr>
               <tr>
                  <td class="key">Kasir :</td>
                  <td>Nur</td>
               </tr>
            </tbody>
         </table>
      </div>-->
   <ul class="stack radius button-group">
      <li><a href="<?php echo $this->createUrl('tambah'); ?>" class="expand bigfont tiny button">New</a></li>
      <li><a href="<?php echo $this->createUrl('suspended'); ?>" class="success expand bigfont tiny button">Suspended</a></li>
   </ul>
</div>
<?php echo $content; ?>
<?php
$this->endContent();
