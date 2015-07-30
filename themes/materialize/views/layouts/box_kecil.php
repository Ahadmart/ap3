<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="row">
		<div class="col s12 l6 offset-l3">
			<div class="card panel">
				<div class="card-content blue-grey-text">
					<span class="card-title hide-on-med-and-up blue-grey-text text-darken-4"><?php echo $this->boxHeader['small']; ?></span>
					<span class="card-title hide-on-small-only blue-grey-text text-darken-4"><?php echo $this->boxHeader['normal']; ?></span>
					<div class="divider"></div>
					<div class="section">
						<?php echo $content; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if (!empty($this->menu)) {
	?>

	<div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
		<a accesskey="m" class="btn-floating btn-large green">
			<i class="large mdi-action-view-module"></i>
		</a>
		<?php
		$this->widget('zii.widgets.CMenu', array(
			 'encodeLabel' => false,
			 'id' => '',
			 'items' => $this->menu,
		))
		?>
	</div>
	<?php
}
/*
 * <li><a class="btn-floating yellow darken-1"><i class="large mdi-editor-format-quote"></i></a></li>
  <li><a class="btn-floating green"><i class="large mdi-editor-publish"></i></a></li>
  <li><a class="btn-floating blue"><i class="large mdi-editor-attach-file"></i></a></li>
 */
$this->endContent();
