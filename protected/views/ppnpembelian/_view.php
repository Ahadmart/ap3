<?php
/* @var $this PpnpembelianController */
/* @var $data PembelianPpn */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pembelian_id')); ?>:</b>
	<?php echo CHtml::encode($data->pembelian_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_faktur_pajak')); ?>:</b>
	<?php echo CHtml::encode($data->no_faktur_pajak); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_ppn_hitung')); ?>:</b>
	<?php echo CHtml::encode($data->total_ppn_hitung); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_ppn_faktur')); ?>:</b>
	<?php echo CHtml::encode($data->total_ppn_faktur); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_by')); ?>:</b>
	<?php echo CHtml::encode($data->updated_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	*/ ?>

</div>